<?php

/*  This code was written by Ihor Slyva
    http://ihorsl.com 
    License GPLv2 */

namespace wpmjgu;

class class_wpmjgu_batch_optimization_worker
{
    public function the_html()
    {
        global $wpmjgu_func;

        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            $this->_die_("Only POST queries supported");
        }
        
        global $ALL_WPMJGU_SETTINGS, $THUMBNAILS_SIZES;
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
        

        define('CURRENT_DIR', $wpmjgu_func->url_file->mbDirname(CURRENT_PATH));        
        define('INITIAL_PATH', $wpmjgu_func->url_file->get_attachment_initial_path(ATTACHMENT_ID, CURRENT_PATH, ORIGINAL_IMAGES_DIRECTORY));
        define('INITIAL_FILENAME', $wpmjgu_func->url_file->mbFilename(INITIAL_PATH));        
        define('INITIAL_FILETYPE', $wpmjgu_func->url_file->path_to_typeflag(INITIAL_PATH));        

 
        print "Initial path: " . INITIAL_PATH . "\n";                  
        print __("Applying Pathfilter to initial path") . "\n";    
        
        $r = $wpmjgu_func->url_file->path_filter_apply(INITIAL_PATH, $ALL_WPMJGU_SETTINGS['wpmjgu_path_filter']);
        if ($r['error'])
        {
            $this->_die_("Faild to apply path filter to file " . INITIAL_PATH . "\n");
        }        
        print $r['out'];
        if ($r['quality'] === 'SKIP')
        {
            $this->_exit_();
        }    
        define('WPMJGU_CURRENT_JPEG_QUALITY', $r['quality']);

        if ($ALL_WPMJGU_SETTINGS["wpmjgu_mode"] === "webp_duplicate")
        {
            $this->webp_duplicate();
        }    
        else
        {
            $this->compress_or_optimize();
        }    
        
        
        $this->_exit_();
    }        
    
    public function compress_or_optimize()
    {
        global $wpmjgu_func;
        global $ATTACHMENT, $THUMBNAILS_SIZES;        

        if (INITIAL_FILETYPE !== $wpmjgu_func->url_file->PNG && INITIAL_FILETYPE !== $wpmjgu_func->url_file->JPG)
        {
            $this->_exit_("Only png and jpg images supported in this mode\n");
        }    
        
        
        $ret = $wpmjgu_func->url_file->backup_original(ATTACHMENT_ID, $ATTACHMENT->guid, CURRENT_PATH, ORIGINAL_IMAGES_DIRECTORY);
        print $ret['out'];
        if (isset($ret['error']))
        {
            $this->_die_("Can't backup image");
        }
        
        define('ORIGINAL_PATH', $ret['original_path']);
        $size = getimagesize(ORIGINAL_PATH);        
        define('ORIGINAL_WIDTH', $size[0]);
        define('ORIGINAL_HEIGHT', $size[1]);        
        unset($size);        
        define('ORIGINAL_FILETYPE', $wpmjgu_func->url_file->path_to_typeflag(ORIGINAL_PATH));
        define('ORIGINAL_SIZE', filesize(ORIGINAL_PATH));        
        define('OUTPUT_PATH_SALT', $wpmjgu_func->url_file->generate_salt(8));

        if (ORIGINAL_FILETYPE === $wpmjgu_func->url_file->PNG)
        {
            define('ORIGINAL_GD', imagecreatefrompng(ORIGINAL_PATH));
            define('ORIGINAL_HAS_ALPHA', $wpmjgu_func->image->does_image_has_alpha(ORIGINAL_GD));
        }    
        else if (ORIGINAL_FILETYPE === $wpmjgu_func->url_file->JPG)
        {
            define('ORIGINAL_GD', imagecreatefromjpeg(ORIGINAL_PATH));
            define('ORIGINAL_HAS_ALPHA', false);
        }              
        
        
        delete_post_meta(ATTACHMENT_ID, 'wpmjgu_optimized');
        $r = $this->create_compressed_or_optimized_image();
        print $r['out'];
        if (isset($r['error']))
        {
            $this->_die_("Optimization faild!\n");
        }
        
        $new_path = $r['output_file'];
        $new_mime = $wpmjgu_func->url_file->typeflag_to_mine($wpmjgu_func->url_file->path_to_typeflag($new_path));        

        $wpmjgu_func->url_file->delete_attachment_thumbnails(CURRENT_PATH);
        $wpmjgu_func->url_file->delete_webp_duplicates(CURRENT_PATH);        
        delete_post_meta(ATTACHMENT_ID, 'wpmjgu_webp_duplicate');
        
        if (CURRENT_PATH !== $new_path)
        {
        print "\n"; 

            if (file_exists(CURRENT_PATH))
            {
                $wpmjgu_func->url_file->wait_and_unlink(CURRENT_PATH);
                print "Current attachment file deleted ".CURRENT_PATH."\n";
            }


            //---- 

            print __("New attachment file") . ": $new_path\n";

            $ATTACHMENT->post_mime_type = $new_mime;
            wp_update_post($ATTACHMENT);

            $new_url = $wpmjgu_func->url_file->wpuploads_path_to_url($new_path);
            
            $wpmjgu_func->url_file->updateAttachmentGuit(ATTACHMENT_ID, $new_url);
            print __("Guid changed to") . ": $new_url\n";        
        }    

        //------

        print "\n";       
        $wpmjgu_func->url_file->delete_attachment_thumbnails($new_path);
        $wpmjgu_func->url_file->delete_webp_duplicates($new_path);        
        $content_url_replace = array();                        
        
        $metadata_new_sizes = array();        

        print "\n";        
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

            $r = $this->create_compressed_or_optimized_image($thumbnail_max_width, $thumbnail_max_height, $size_properties['crop']);
            print $r['out'];
            if (isset($r['error']))
            {
                $this->_die_("Thumbail not created!\n");
            }
            $new_thumbnail_path = $r['output_file'];

            print "File " . $new_thumbnail_path . "\n\n";

            $add_to_sizes = array();
            //$add_to_sizes['file'] = pathinfo($new_thumbnail_path, PATHINFO_BASENAME);
            $add_to_sizes['file'] = $wpmjgu_func->url_file->mbBasename($new_thumbnail_path);
            list($add_to_sizes['width'], $add_to_sizes['height']) = getimagesize($new_thumbnail_path);
            $add_to_sizes['mime-type'] = $wpmjgu_func->url_file->typeflag_to_mine($wpmjgu_func->url_file->path_to_typeflag($new_thumbnail_path));

            $metadata_new_sizes[$size_name] = $add_to_sizes;

        }

        //----

        
        $metadata = wp_get_attachment_metadata(ATTACHMENT_ID);                
        //print_r($ATTACHMENT);
        //print_r($metadata);

        if ($new_path !== CURRENT_PATH)
        {
            $old_attachment_file_relative_path = $metadata['file'];
            $new_attachment_file_relative_path = $wpmjgu_func->url_file->relative_path($new_path, $wpmjgu_func->url_file->UPLOADS_DIR, true);
            $content_url_replace[$old_attachment_file_relative_path] = $new_attachment_file_relative_path;
            $metadata['file'] = $new_attachment_file_relative_path;
        }

        //$attachment_file_relative_path_dirname = dirname($metadata['file']);
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
        
        update_post_meta(ATTACHMENT_ID, 'wpmjgu_optimized', 'true');
        
        
    }        

    public function create_compressed_or_optimized_image($max_width = null, $max_height = null, $crop = null)
    {
        global $wpmjgu_func, $ALL_WPMJGU_SETTINGS;
        $ret['out'] = "";
     
        $rand = $wpmjgu_func->url_file->generate_salt(32);
        $thumbnail_temp_png = $temp_png = $ALL_WPMJGU_SETTINGS['wpmjgu_plugin_working_directory'] . DIRECTORY_SEPARATOR . "temp" . DIRECTORY_SEPARATOR . "temp_" . $rand . ".png";
        $thumbnail_temp_jpg = $compressed_temp_jpg = $ALL_WPMJGU_SETTINGS['wpmjgu_plugin_working_directory'] . DIRECTORY_SEPARATOR . "temp" .  DIRECTORY_SEPARATOR . "compressed_temp_".$rand.".jpg";    
                              $optimized_temp_jpg = $ALL_WPMJGU_SETTINGS['wpmjgu_plugin_working_directory'] . DIRECTORY_SEPARATOR . "temp" .  DIRECTORY_SEPARATOR . "optimized_temp_".$rand.".jpg";    


        if (ORIGINAL_GD === false)
        {
            $ret['out'] .= "Can't open image " . ORIGINAL_PATH . "\n";
            $ret['error'] = true;
            return $ret;            
        }    
        
       
        
        if ($max_width || $max_height)   //Thumbnail generation
        {

            $thumbnail_gd = $wpmjgu_func->image->wp_image_resize_crop(ORIGINAL_GD, $max_width, $max_height, $crop, ORIGINAL_HAS_ALPHA );
            
            $thumbnail_width = imagesx($thumbnail_gd ); //current width
            $thumbnail_height = imagesy($thumbnail_gd ); //current height        

            // Thumbnail with alpha -----------------------------
            
            if (ORIGINAL_HAS_ALPHA && ORIGINAL_FILETYPE === $wpmjgu_func->url_file->PNG )
            {
                // Repcale ATTACHMENT_ID width ATTACHMENT_ID . unixt
                $ret['output_file'] = CURRENT_DIR . DIRECTORY_SEPARATOR . INITIAL_FILENAME . "-" . OUTPUT_PATH_SALT  . "-" . $thumbnail_width . "x" . $thumbnail_height . ".png";
                $wpmjgu_func->url_file->wait_and_unlink($ret['output_file']);
                imagepng($thumbnail_gd, $ret['output_file'], 9, PNG_ALL_FILTERS);
                $ret['out'] .= "PNG thumbnail created for image with transparency.\n";
                return  $ret;
            }

            // Non alpha thumbnail ---------------------------------

            imagepng($thumbnail_gd, $thumbnail_temp_png, 9, PNG_ALL_FILTERS);
            
            $r = $wpmjgu_func->image->jpeg_compress_wrapper($thumbnail_temp_png, $thumbnail_temp_jpg);    
            $ret['out'] .= $r['out'];
            if (isset($r['error']))
            {
                $ret['out'] .= "Faild to create temporary jpg file for thumbnail\n";
                $ret['error'] = true;
                $wpmjgu_func->url_file->wait_and_unlink($thumbnail_temp_png);                
                return $ret;                
            }    
            
            
            $thumbnail_temp_png_size = filesize($thumbnail_temp_png);            
            $thumbnail_temp_jpg_size = filesize($thumbnail_temp_jpg);
            $ret['out'] .= "Jpeg variant has $thumbnail_temp_jpg_size bytes\n";
            $ret['out'] .= "Png variant has $thumbnail_temp_png_size bytes\n";
            
            $jpg_save_percentage = round(100 - ($thumbnail_temp_jpg_size * 100 / $thumbnail_temp_png_size));            

            if ($thumbnail_temp_jpg_size > $thumbnail_temp_png_size )
            {
                $ret['out'] .= "Jpeg thumbnail is larger then png. Not using it.\n";
            }    
            else if ($jpg_save_percentage < (int) $ALL_WPMJGU_SETTINGS['wpmjgu_jpeg_compression_min_benefit']) 
            {
                $ret['out'] .= "The benefit from jpeg thumbnail is not enough. ($jpg_save_percentage%)\n";
            }
            else
            {
                // JPEG thumbnail
                
                $ret['output_file'] = CURRENT_DIR . DIRECTORY_SEPARATOR . INITIAL_FILENAME . "-" . OUTPUT_PATH_SALT . "-" . $thumbnail_width . "x" . $thumbnail_height . ".jpg";
                $wpmjgu_func->url_file->wait_and_unlink($ret['output_file']);
                copy($thumbnail_temp_jpg, $ret['output_file']);
        
                $ret['out'] .= "Thumbnail created in jpeg\n";

                $wpmjgu_func->url_file->wait_and_unlink($thumbnail_temp_png);                
                $wpmjgu_func->url_file->wait_and_unlink($thumbnail_temp_jpg);                                
                return $ret;            
            }    


            // Png thumbnail
            $ret['output_file'] = CURRENT_DIR . DIRECTORY_SEPARATOR . INITIAL_FILENAME . "-" . OUTPUT_PATH_SALT . "-" . $thumbnail_width . "x" . $thumbnail_height . ".png";
            $wpmjgu_func->url_file->wait_and_unlink($ret['output_file']);
            copy($thumbnail_temp_png, $ret['output_file']);
            $ret['out'] .= "Png thumbnail created.\n";

            $wpmjgu_func->url_file->wait_and_unlink($thumbnail_temp_png);                
            $wpmjgu_func->url_file->wait_and_unlink($thumbnail_temp_jpg);           
            return  $ret;        

        }
        else
        {      // Atachment image generation

            $ret['out'] .= "Original image has " . ORIGINAL_SIZE . " bytes\n";
            
            // Attachment with alpha ----------------------
            if (ORIGINAL_HAS_ALPHA  && ORIGINAL_FILETYPE === $wpmjgu_func->url_file->PNG  )
            {
                $ret['output_file'] = CURRENT_DIR . DIRECTORY_SEPARATOR . INITIAL_FILENAME . "-" . OUTPUT_PATH_SALT . ".png";

                imagealphablending(ORIGINAL_GD, false);
                imagesavealpha(ORIGINAL_GD, true);                 
                imagepng(ORIGINAL_GD, $temp_png, 9, PNG_ALL_FILTERS);
                $temp_png_size = filesize($temp_png);                 
                $ret['out'] .= "png with transparency\nOptimized png variant has $temp_png_size bytes\n" ;

                
                $benefit = ORIGINAL_SIZE - $temp_png_size;

                $wpmjgu_func->url_file->wait_and_unlink($ret['output_file']);
                if ( $benefit > 0 )
                {

                    copy($temp_png, $ret['output_file']);
                    $ret['out'] .= "Using optimized image. Saved ".round($benefit * 100  / ORIGINAL_SIZE)."%\n"; 
                }
                else
                {
                    copy(ORIGINAL_PATH, $ret['output_file']);
                    $ret['out'] .= "No optimization. Original file used.\n";                 
                }

                $wpmjgu_func->url_file->wait_and_unlink($temp_png);                
                return $ret;

            }
            else
            {
                // Non alpha atachment ---------------------

                $variants = array();                
                $variants['original'] = ORIGINAL_SIZE - 1;
                //----                
                
                imagepng(ORIGINAL_GD, $temp_png, 9, PNG_ALL_FILTERS);
                $temp_png_size = filesize($temp_png);                 
                $variants['png'] = $temp_png_size;
                
                //----
                
                $r = $wpmjgu_func->image->jpeg_compress_wrapper($temp_png, $compressed_temp_jpg);
                $ret['out'] .= $r['out'];
                if (isset($r['error']))
                {
                    $ret['out'] .= "Faild to create temporary jpg file\n";
                    $ret['error'] = true;
                    $wpmjgu_func->url_file->wait_and_unlink($thumbnail_temp_png);                     
                    return $ret;                
                }    
                $compressed_temp_jpg_size = filesize($compressed_temp_jpg);
                $variants['jpeg_compressed'] = $compressed_temp_jpg_size;
                
                //----

                if (ORIGINAL_FILETYPE === $wpmjgu_func->url_file->JPG)
                {    
                    $r = $wpmjgu_func->image->jpeg_compress_wrapper(ORIGINAL_PATH, $optimized_temp_jpg, true);
                    $ret['out'] .= $r['out'];
                    if (isset($r['error']))
                    {
                        $ret['out'] .= "Faild to create optimized temporary jpg file\n";
                        $ret['error'] = true;
                        $wpmjgu_func->url_file->wait_and_unlink($thumbnail_temp_png);                                             
                        return $ret;                
                    }  

                    if (! isset($r['not_supported']))
                    {
                        $optimized_temp_jpg_size = filesize($optimized_temp_jpg);
                        $variants['jpeg_optimized'] = $optimized_temp_jpg_size;
                    }    
                }
                
                //----
                
                asort($variants);
                reset($variants);
                
                foreach ($variants as $variant => $filesize)
                {
                   if ($variant === 'original') { continue; }
                   $ret['out'] .= $variant . " variant has $filesize bytes\n" ;
                }    
                
                
                foreach ($variants as $variant => $filesize)
                {


                    switch ($variant)
                    {
                        case 'jpeg_compressed':

                            $save_percentage = round(100 - ($compressed_temp_jpg_size * 100 / ORIGINAL_SIZE));

                            if ($compressed_temp_jpg_size > ORIGINAL_SIZE)
                            {
                                $ret['out'] .= "Compressed jpeg file is larger then original. Not using it.\n";
                                break;
                            }    
                            else if ( $save_percentage < (int) $ALL_WPMJGU_SETTINGS['wpmjgu_jpeg_compression_min_benefit'])
                            {
                                $ret['out'] .= "The benefit from jpeg compression is not enough ($save_percentage%)\n";
                                break;
                            }
                            else
                            {

                                $ret['output_file'] = CURRENT_DIR . DIRECTORY_SEPARATOR . INITIAL_FILENAME . "-" . OUTPUT_PATH_SALT . ".jpg";
                                $wpmjgu_func->url_file->wait_and_unlink($ret['output_file']);
                                copy($compressed_temp_jpg, $ret['output_file']);
                                $ret['out'] .= "Compressed with jpeg. New image has $compressed_temp_jpg_size bytes. Saved $save_percentage%.\n";

                                break 2;
                            }                                

                        break 2;

                        //-------------------------------------------

                        case 'jpeg_optimized':
                            $save_percentage = round(100 - ($optimized_temp_jpg_size * 100 / ORIGINAL_SIZE));
                            $ret['out'] .= "Jpeg lossless optimization used. New image has $optimized_temp_jpg_size bytes. Saved $save_percentage%\n";
                            $ret['output_file'] = CURRENT_DIR . DIRECTORY_SEPARATOR . INITIAL_FILENAME . "-" . OUTPUT_PATH_SALT . ".jpg";
                            $wpmjgu_func->url_file->wait_and_unlink($ret['output_file']);
                            copy($optimized_temp_jpg, $ret['output_file']);
                        break 2;    

                        case 'png':
                            $save_percentage = round(100 - ($temp_png_size * 100 / ORIGINAL_SIZE));
                            $ret['out'] .= "Png optimization used. New image has $temp_png_size bytes. Saved $save_percentage%\n";
                            $ret['output_file'] = CURRENT_DIR . DIRECTORY_SEPARATOR . INITIAL_FILENAME . "-" . OUTPUT_PATH_SALT . ".png";
                            $wpmjgu_func->url_file->wait_and_unlink($ret['output_file']);
                            copy($temp_png, $ret['output_file']);
                        break 2; 

                        case 'original':                    
                            $ret['output_file'] = CURRENT_DIR . DIRECTORY_SEPARATOR . INITIAL_FILENAME . "-" . OUTPUT_PATH_SALT . "." . $wpmjgu_func->url_file->mbExt(INITIAL_PATH);
                            $ret['out'] .= "No optimization. Original image used\n";                    
                            $wpmjgu_func->url_file->wait_and_unlink($ret['output_file']);
                            copy (ORIGINAL_PATH, $ret['output_file']);
                        break 2;    

                    }
                }    

                $wpmjgu_func->url_file->wait_and_unlink($optimized_temp_jpg);                     
                $wpmjgu_func->url_file->wait_and_unlink($compressed_temp_jpg);                     
                $wpmjgu_func->url_file->wait_and_unlink($temp_png);                                         
                $wpmjgu_func->url_file->wait_and_unlink($optimized_temp_jpg);                                                         

                return $ret;

                
            }
        }
        
    }    

    public function webp_duplicate()
    {
        global $wpmjgu_func, $ATTACHMENT, $ALL_WPMJGU_SETTINGS, $THUMBNAILS_SIZES;
        
        if (INITIAL_FILETYPE !== $wpmjgu_func->url_file->GIF && INITIAL_FILETYPE !== $wpmjgu_func->url_file->PNG && INITIAL_FILETYPE !== $wpmjgu_func->url_file->JPG)
        {
            $this->_exit_("Only png, gif and jpg images supported in this mode\n");
        }         
        
        
        $backup_file_path = $wpmjgu_func->url_file->has_backup($ATTACHMENT->ID, CURRENT_PATH, ORIGINAL_IMAGES_DIRECTORY);
        
        if ($backup_file_path)
        {
            define('ORIGINAL_PATH', $backup_file_path);
            print "Backed up original image found at $backup_file_path\n";
        }
        else
        {
            if (file_exists(CURRENT_PATH))
            {    
                define('ORIGINAL_PATH', CURRENT_PATH);
            }
            else
            {
                $this->_die_("Current file not exists\n");
            }    
        }    
        

        $wpmjgu_func->url_file->delete_webp_duplicates(CURRENT_PATH);
        delete_post_meta(ATTACHMENT_ID, 'wpmjgu_webp_duplicate');
        
        $size = getimagesize(ORIGINAL_PATH);        
        define('ORIGINAL_WIDTH', $size[0]);
        define('ORIGINAL_HEIGHT', $size[1]);        
        unset($size);        
        define('ORIGINAL_FILETYPE', $wpmjgu_func->url_file->path_to_typeflag(ORIGINAL_PATH));
        define('ORIGINAL_SIZE', filesize(ORIGINAL_PATH));
        print "Original image has " . ORIGINAL_SIZE . " bytes\n";

        //---
        
        $webp_path = CURRENT_PATH . ".webp";
        if (ORIGINAL_FILETYPE === $wpmjgu_func->url_file->GIF)
        {
            $r = $wpmjgu_func->image->jpeg_compress_constant_quality(ORIGINAL_PATH, $webp_path, "gif2webp",  WPMJGU_CURRENT_JPEG_QUALITY, (int) WPMJGU_CURRENT_JPEG_QUALITY === 100 ? true : false);
        }    
        else
        {
            $r = $wpmjgu_func->image->jpeg_compress_constant_quality(ORIGINAL_PATH, $webp_path, "webp",  WPMJGU_CURRENT_JPEG_QUALITY, (int) WPMJGU_CURRENT_JPEG_QUALITY === 100 ? true : false);
        }
        
        if (isset($r['error']))
        {
            $this->_die_($r['out']);
        }
        $webp_size = filesize($webp_path);
        print "Webp image has $webp_size bytes\n";        
        
        //---
        
        if (file_exists(CURRENT_PATH))
        {
            $current_size = filesize(CURRENT_PATH);
            $save_percentage = round(100 - ($webp_size * 100 / $current_size));
            print "Current image has $current_size bytes\n";
            print "Webp images is ";
            if ($save_percentage > 0) 
            {
                print "$save_percentage% smaller then current\n";
            }
            else if ($save_percentage < 0)
            {
                print abs($save_percentage) . "% larger then current\n";
            }    
        }
        else
        {
            $current_size = 0;
        }    

        
        if ($current_size !== 0 && $current_size < $webp_size)
        {
            print "No need in webp duplicate\n";
            $wpmjgu_func->url_file->wait_and_unlink($webp_path);
        }    
        else if ($current_size !== 0 && $save_percentage < (int) $ALL_WPMJGU_SETTINGS['wpmjgu_jpeg_compression_min_benefit'] && (int) WPMJGU_CURRENT_JPEG_QUALITY !== 100)        
        {
            print "The benefit from webp is not enough ($save_percentage%). Webp not created\n";
            $wpmjgu_func->url_file->wait_and_unlink($webp_path);
        }
        else
        {    
            print "Webp image generated $webp_path\n";
        }
        
        
        //-----------  thumbnails ---------------------------

        if (!defined('ORIGINAL_GD') && !defined('ORIGINAL_HAS_ALPHA')) {
            if (ORIGINAL_FILETYPE === $wpmjgu_func->url_file->PNG) {
                define('ORIGINAL_GD', imagecreatefrompng(ORIGINAL_PATH));
                define('ORIGINAL_HAS_ALPHA', $wpmjgu_func->image->does_image_has_alpha(ORIGINAL_GD));    
            }    
            else if (ORIGINAL_FILETYPE === $wpmjgu_func->url_file->JPG) {
                define('ORIGINAL_GD', imagecreatefromjpeg(ORIGINAL_PATH));
                define('ORIGINAL_HAS_ALPHA', false);
            }        
        }

        $metadata = wp_get_attachment_metadata(ATTACHMENT_ID);  
        $rand = $wpmjgu_func->url_file->generate_salt(32);
        $temp_png = $ALL_WPMJGU_SETTINGS['wpmjgu_plugin_working_directory'] .  "/temp/webp_duplicate_thumbnail_".$rand.".png";                
        
        print "\n";        
        foreach ($THUMBNAILS_SIZES as $size_name => $size_properties)
        {
            print "\n";                    
            
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
                print "Skip. Image smaller then thumbnail\n";
                continue;
            }  
        
            //---
            
            $current_path = "";
            $current_size = 0;
            
            if (isset($metadata['sizes'][$size_name]['file']))
            {
                // $current_path = $wpmjgu_func->url_file->UPLOADS_DIR . "/" . $wpmjgu_func->url_file->mbDirname($metadata['file']) . "/" . $metadata['sizes'][$size_name]['file'];
                $current_path = $wpmjgu_func->url_file->UPLOADS_DIR . "/" . $metadata['sizes'][$size_name]['file'];
            }    
            else
            {
                print "Thumbnail data not exists in attachment meta\n";
                continue;                
            }    
            
            print "Current thumbnail path $current_path\n";
            if (!file_exists($current_path))
            {
                print "Current thumbnail file not exists. Webp duplicate not created\n";
                continue;
            }    
            else
            {
                $current_size = filesize($current_path);
                print "Current thumbnail has $current_size bytes\n";
            }   
            

            //---

            $webp_path = $current_path . ".webp";            
            
            if ($wpmjgu_func->url_file->path_to_typeflag($current_path) !== $wpmjgu_func->url_file->GIF)
            {    
                $thumbnail_gd = $wpmjgu_func->image->wp_image_resize_crop(ORIGINAL_GD, $thumbnail_max_width, $thumbnail_max_height, $size_properties['crop'], ORIGINAL_HAS_ALPHA);
            
                imagepng($thumbnail_gd, $temp_png, 0, PNG_NO_FILTER);
                $r = $wpmjgu_func->image->jpeg_compress_constant_quality($temp_png, $webp_path, "webp",  WPMJGU_CURRENT_JPEG_QUALITY, (int) WPMJGU_CURRENT_JPEG_QUALITY === 100 ? true : false);
                $wpmjgu_func->url_file->wait_and_unlink($temp_png);                
            }
            else
            {
                $r = $wpmjgu_func->image->jpeg_compress_constant_quality($current_path, $webp_path, "gif2webp",  WPMJGU_CURRENT_JPEG_QUALITY,  (int) WPMJGU_CURRENT_JPEG_QUALITY === 100 ? true : false);
            }

            if (isset($r['error']))
            {
                $this->_die_($r['out']);
            }
            
            //---

            $webp_size = filesize($webp_path);
            print "Webp thumbnail has $webp_size bytes, and is ";             
            $save_percentage = round(100 - ($webp_size * 100 / $current_size));
            if ($save_percentage > 0) 
            {
                print "$save_percentage% smaller then current\n";
            }
            else if ($save_percentage < 0)
            {
                print abs($save_percentage) . "% larger then current\n";
            } 
            
            //----
    
            if ($current_size !== 0 && $current_size < $webp_size)
            {
                print "No need in webp duplicate\n";
                $wpmjgu_func->url_file->wait_and_unlink($webp_path);
            }    
            else if ($current_size !== 0 && $save_percentage < (int) $ALL_WPMJGU_SETTINGS['wpmjgu_jpeg_compression_min_benefit']  &&  (int) WPMJGU_CURRENT_JPEG_QUALITY !== 100)        
            {
                print "The benefit from webp thumbnail is not enough ($save_percentage%). Webp not created\n";
                $wpmjgu_func->url_file->wait_and_unlink($webp_path);
            }
            else
            {    
                print "Webp thumbnail created $webp_path\n";
            }            
            
        }
        
  
        update_post_meta(ATTACHMENT_ID, 'wpmjgu_webp_duplicate', 'true');
        
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

