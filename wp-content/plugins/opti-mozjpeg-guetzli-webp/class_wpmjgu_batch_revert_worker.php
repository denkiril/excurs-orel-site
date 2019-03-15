<?php

/*  This code was written by Ihor Slyva
    http://ihorsl.com 
    License GPLv2 */

namespace wpmjgu;

class class_wpmjgu_batch_revert_worker
{
    public function the_html()
    {
        global $wpmjgu_func;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            $this->_die_("Only POST queries supported");
        }
        
        define('WORDPRESS_JPEG_QUALITY', apply_filters('jpeg_quality', '75'));
        global $ALL_WPMJGU_SETTINGS;
        $ALL_WPMJGU_SETTINGS = $wpmjgu_func->get_all_wpmjgu_settings_from_post_query();
        define('ORIGINAL_IMAGES_DIRECTORY', $ALL_WPMJGU_SETTINGS['wpmjgu_plugin_working_directory'] . DIRECTORY_SEPARATOR . $wpmjgu_func->url_file->ORIGINAL_IMAGES_SUBDIRECTORY);        
        
        $THUMBNAILS_SIZES = $wpmjgu_func->image->get_thumbnails_sizes();

        define('ATTACHMENT_ID', $_POST['id']);        
        global $ATTACHMENT;
        $ATTACHMENT= get_post(ATTACHMENT_ID);
        print "\n\n---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n";

        print "Attachment ID: " . ATTACHMENT_ID . "\n";
        print "Guid: {$ATTACHMENT->guid}\n";

        define('CURRENT_URL', $wpmjgu_func->url_file->get_attachment_url(ATTACHMENT_ID, $ATTACHMENT->guid));
        if (! CURRENT_URL)
        {
            $this->_die_(__("Can't determine attachment url"));
        }    
        print "Current url: " . CURRENT_URL . "\n";         
        
        define('CURRENT_PATH', $wpmjgu_func->url_file->wpuploads_url_to_path(CURRENT_URL));
        if (! CURRENT_PATH)
        {
            $this->_die_(__("Can't convert attachment url into filesystem path. Your Wordpress uploads url is {$wpmjgu_func->url_file->UPLOADS_URL}"));
        }          
        print "Current path: " . CURRENT_PATH . "\n";        
        

        //define('CURRENT_DIR', dirname(CURRENT_PATH));
        define('CURRENT_DIR', $wpmjgu_func->url_file->mbDirname(CURRENT_PATH));        
        define('INITIAL_PATH', $wpmjgu_func->url_file->get_attachment_initial_path(ATTACHMENT_ID, CURRENT_PATH, ORIGINAL_IMAGES_DIRECTORY));
        //define('INITIAL_FILENAME', pathinfo(INITIAL_PATH,  PATHINFO_FILENAME));
        define('INITIAL_FILENAME', $wpmjgu_func->url_file->mbFilename(INITIAL_PATH));
        print "Initial path: " . INITIAL_PATH . "\n";                  
 
        //----        
        
        $wpmjgu_func->url_file->delete_webp_duplicates(CURRENT_PATH);        
        delete_post_meta(ATTACHMENT_ID, 'wpmjgu_webp_duplicate');

        // This code should be here, because script may exit in next lines, and webp duplicates won't be deleted 
        //----
        
        define('ORIGINAL_PATH', $wpmjgu_func->url_file->has_backup(ATTACHMENT_ID, CURRENT_PATH, ORIGINAL_IMAGES_DIRECTORY));
        if (! ORIGINAL_PATH)
        {
            $this->_exit_("No backed up original found.");
        }    
        else
        {
            print "Backed up original image found at " . ORIGINAL_PATH . "\n";
        }
        define('ORIGINAL_FILETYPE', $wpmjgu_func->url_file->path_to_typeflag(ORIGINAL_PATH));        
        $size = getimagesize(ORIGINAL_PATH);        
        define('ORIGINAL_WIDTH', $size[0]);
        define('ORIGINAL_HEIGHT', $size[1]);        
        unset($size);  
        
        if (ORIGINAL_FILETYPE === $wpmjgu_func->url_file->PNG)
        {
            define('ORIGINAL_GD',imagecreatefrompng(ORIGINAL_PATH));
            define('ORIGINAL_HAS_ALPHA', $wpmjgu_func->image->does_image_has_alpha(ORIGINAL_GD));    
        }    
        else if (ORIGINAL_FILETYPE === $wpmjgu_func->url_file->JPG)
        {
            define('ORIGINAL_GD', imagecreatefromjpeg(ORIGINAL_PATH));
            define('ORIGINAL_HAS_ALPHA',  false);
        }        
        else
        {
            $this->_die_("ORIGINAL_FILETYPE wrong " . ORIGINAL_FILETYPE);
        }

        if (ORIGINAL_GD === false)
        {
            $this->_die_("Can't open image " . ORIGINAL_PATH );

        }   

        //----
        
        $wpmjgu_func->url_file->delete_attachment_thumbnails(CURRENT_PATH);
        $wpmjgu_func->url_file->wait_and_unlink(CURRENT_PATH);
        print "Current image deleted \n";
        
        copy(ORIGINAL_PATH, INITIAL_PATH );
        print "Inital image restored from backup\n\n";
        
        $wpmjgu_func->url_file->delete_attachment_thumbnails(INITIAL_PATH);        
        $content_url_replace = array();

        $metadata_new_sizes = array();        
        
        foreach ($THUMBNAILS_SIZES as $size_name => $size_properties)
        {
            $thumbnail_max_width = (int) $size_properties['width'];
            $thumbnail_max_height = (int) $size_properties['height'];

            print "Creating thumbnail \"" . $size_name . "\" " .  $thumbnail_max_width . "x" . $thumbnail_max_height;
            if ($size_properties['crop'] === true) { print "  crop"; }
            if (is_array($size_properties['crop'])) { print "  crop=" . $size_properties['crop'][0] . "-" . $size_properties['crop'][1] ; }

            print "\n";
            
            if( ($thumbnail_max_width > ORIGINAL_WIDTH && $thumbnail_max_height > ORIGINAL_HEIGHT) ||
                ($thumbnail_max_width == 0 && $thumbnail_max_height > ORIGINAL_HEIGHT) ||
                ($thumbnail_max_width > ORIGINAL_WIDTH && $thumbnail_max_height == 0)  )
            {
                print "Skip. Image smaller then thumbnail\n\n";
                continue;
            }  
            
            $thumbnail_gd = $wpmjgu_func->image->wp_image_resize_crop(ORIGINAL_GD, $thumbnail_max_width, $thumbnail_max_height, $size_properties['crop'], ORIGINAL_HAS_ALPHA);


            $thumbnail_width = imagesx($thumbnail_gd ); //current width
            $thumbnail_height = imagesy($thumbnail_gd ); //current height 

            $thumbnail_path = CURRENT_DIR . DIRECTORY_SEPARATOR . INITIAL_FILENAME . "-" . $thumbnail_width . "x" . $thumbnail_height . "." .  $wpmjgu_func->url_file->mbExt(INITIAL_PATH);                        
            if (ORIGINAL_FILETYPE === $wpmjgu_func->url_file->PNG)
            {
                imagepng($thumbnail_gd, $thumbnail_path, 9, PNG_ALL_FILTERS);
            }    
            else if (ORIGINAL_FILETYPE === $wpmjgu_func->url_file->JPG)
            {
                imagejpeg($thumbnail_gd, $thumbnail_path, WORDPRESS_JPEG_QUALITY);
            }              

            print "File " . $thumbnail_path . "\n\n";

            $add_to_sizes = array();
            $add_to_sizes['file'] =  $wpmjgu_func->url_file->mbBasename($thumbnail_path);            
            $add_to_sizes['width'] = $thumbnail_width;
            $add_to_sizes['height'] = $thumbnail_height;
            $add_to_sizes['mime-type'] = $wpmjgu_func->url_file->typeflag_to_mine(ORIGINAL_FILETYPE);

            $metadata_new_sizes[$size_name] = $add_to_sizes;
            
        }
        
        //----

        $backuped_guid_file_path = ORIGINAL_IMAGES_DIRECTORY . "/" . ATTACHMENT_ID . "_guid.txt";
        if (file_exists($backuped_guid_file_path)) {
            $backuped_guid = file_get_contents($backuped_guid_file_path);
        } else {
            $backuped_guid = null;
        }    
        
        $metadata = wp_get_attachment_metadata(ATTACHMENT_ID);                


        if (CURRENT_PATH !== INITIAL_PATH)
        {
            $ATTACHMENT->post_mime_type = $wpmjgu_func->url_file->typeflag_to_mine($wpmjgu_func->url_file->path_to_typeflag(ORIGINAL_PATH));
            wp_update_post($ATTACHMENT);

            $new_url = $wpmjgu_func->url_file->wpuploads_path_to_url(INITIAL_PATH);

            if (! $backuped_guid) {    
                $wpmjgu_func->url_file->updateAttachmentGuit(ATTACHMENT_ID, $new_url);
                print __("Guid changed to") . ": $new_url (" . __("guid backup not found") . ")\n";
            }

            $old_attachment_file_relative_path = $metadata['file'];
            $new_attachment_file_relative_path = $wpmjgu_func->url_file->relative_path(INITIAL_PATH, $wpmjgu_func->url_file->UPLOADS_DIR, true);
            $content_url_replace[$old_attachment_file_relative_path] = $new_attachment_file_relative_path;
            $metadata['file'] = $new_attachment_file_relative_path;
            
            print "Restored initial attachment url\n";            
        }

        if ($backuped_guid) {
            $wpmjgu_func->url_file->updateAttachmentGuit(ATTACHMENT_ID, $backuped_guid);
            print __("Guid changed to ") . ": $backuped_guid\n";        
        }

        $attachment_file_relative_path_dirname = $wpmjgu_func->url_file->mbDirname($metadata['file']);
        
        foreach ($metadata_new_sizes as $name => $new_data)
        {

            if (isset($metadata['sizes'][$name]))
            {
                $tumbnail_old_basename = $metadata['sizes'][$name]['file'];
                $tumbnail_new_basename = $new_data['file'];

                if ($tumbnail_old_basename !== $tumbnail_new_basename)
                {
                    $content_url_replace[$attachment_file_relative_path_dirname . DIRECTORY_SEPARATOR . $tumbnail_old_basename] = $attachment_file_relative_path_dirname . DIRECTORY_SEPARATOR . $tumbnail_new_basename;
                }    

            }    

        }    


        $metadata['sizes'] = $metadata_new_sizes;

        wp_update_attachment_metadata( ATTACHMENT_ID, $metadata ); 


        //---------------------  
        
        $r = $wpmjgu_func->url_file->content_url_replace($content_url_replace);
        print $r['out'];
        if (isset($r['error']))
        {
            $this->_die_("Faild to update urls in database");
        }  

        
        //--------------------- 

        delete_post_meta(ATTACHMENT_ID, 'wpmjgu_optimized');        
        
        $this->_exit_();
        
    }

    public function _exit_($message = "")
    {
        print $message;
        die();
    }     
    
    public function _die_($message = "")
    {
        print "WPMJGUERROR: " . $message;
        die();
    }       
    
}    
