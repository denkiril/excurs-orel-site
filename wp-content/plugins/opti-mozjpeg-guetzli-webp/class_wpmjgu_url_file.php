<?php

/*  This code was written by Ihor Slyva
    https://ihorsl.com 
    License GPLv2 */

namespace wpmjgu;

class class_wpmjgu_url_file
{

    public $SITE_HOME_URL;
    public $SITE_DOCUMENT_ROOT;

    public $PLUGIN_DIRECTORY;
    public $ORIGINAL_IMAGES_SUBDIRECTORY;
    public $UPLOADS_DIR;
    public $UPLOADS_URL;

    public $PLUGIN_SLUG;
    public $PLUGIN_DEFAULT_WORKING_DIRECTORY;


    public function __construct()
    {
        global $wpmjgu_func;

        //$this->PLUGIN_DIRECTORY = rtrim(plugin_dir_path(__FILE__), DIRECTORY_SEPARATOR);
        $this->PLUGIN_DIRECTORY = $wpmjgu_func->string->mbRTrim(plugin_dir_path(__FILE__), '\/');

        $wp_upload_dir = wp_upload_dir();
        //$this->UPLOADS_DIR = rtrim($wp_upload_dir['basedir'], DIRECTORY_SEPARATOR);
        $this->UPLOADS_DIR = $wpmjgu_func->string->mbRTrim($wp_upload_dir['basedir'], '\/');   

        //$this->UPLOADS_URL = rtrim($wp_upload_dir['baseurl'], "/");
        $this->UPLOADS_URL = $wpmjgu_func->string->mbRTrim($wp_upload_dir['baseurl'], "/");

        //$this->PLUGIN_SLUG = basename($this->PLUGIN_DIRECTORY);
        $this->PLUGIN_SLUG = $this->mbBasename($this->PLUGIN_DIRECTORY);

        $this->PLUGIN_DEFAULT_WORKING_DIRECTORY = $this->UPLOADS_DIR . DIRECTORY_SEPARATOR . $this->PLUGIN_SLUG ;
        $this->ORIGINAL_IMAGES_SUBDIRECTORY = "original-images";

        $this->TYPE_UNDEFINED = 0;
        $this->PNG = 1;
        $this->JPG = 2;
        $this->GIF = 4;
        $this->WEBP = 8;


    } 

    public function get_attachment_url($attachment_id, $attachment_guid)
    {
        $metadata = wp_get_attachment_metadata($attachment_id);
        if (isset ($metadata['file']))
        {    
            return $this->UPLOADS_URL . "/" . $metadata['file'];
        }
        else if (mb_stripos($attachment_guid, 'http') === 0)
        {
            return $attachment_guid;
        }
        else 
        {
            return false;
        }    
    }        


    public function wpuploads_path_to_url($path)
    {

        if (mb_strpos($path, $this->UPLOADS_DIR) === 0)
        {
            return $this->UPLOADS_URL . mb_substr($path, mb_strlen($this->UPLOADS_DIR));
        }
        else
        {
            return null;
        }
    }


    public function wpuploads_url_to_path($url)
    {


        if (mb_strpos($url, $this->UPLOADS_URL) === 0)
        {
            return $this->UPLOADS_DIR . mb_substr($url, mb_strlen($this->UPLOADS_URL));
        }
        else
        {
            return null;
        }    

    }


    public function relative_path($absolute_path, $root, $trim_first_slash = false)
    {


        $trim_first_slash = (int) $trim_first_slash;

        if (mb_strpos($absolute_path, $root) === false)
        {
            return false;
        }        
        else
        {
            $ret =  mb_substr($absolute_path, mb_strlen($root) + $trim_first_slash);
            if (mb_strlen($ret) === 0) { $ret = "./"; }
            return $ret;
        }           
    }         


    public function has_backup($id, $current_path, $originals_dir)
    {
        if (file_exists($originals_dir . DIRECTORY_SEPARATOR . $id . ".png"))
        {
            return $originals_dir . DIRECTORY_SEPARATOR . $id . ".png";
        }
        else if (file_exists($originals_dir . DIRECTORY_SEPARATOR . $id . ".jpg"))
        {
            return $originals_dir . DIRECTORY_SEPARATOR . $id . ".jpg";
        }
        else
        {
            return false;
        }

    }        


    public function get_attachment_initial_path($id, $current_path, $originals_dir)
    {
        global $wpmjgu_func;
        
        //$current_directory = dirname($current_path);
        $current_directory = $this->mbDirname($current_path);    

        if (file_exists($originals_dir . DIRECTORY_SEPARATOR . $id . "_basename.txt"))
        {
            $initial_basename = $wpmjgu_func->string->mbTrim(file_get_contents($originals_dir . DIRECTORY_SEPARATOR . $id . "_basename.txt"));
        }  
        else if (file_exists($originals_dir . DIRECTORY_SEPARATOR . $id . ".txt"))
        {
            $initial_basename = $wpmjgu_func->string->mbTrim(file_get_contents($originals_dir . DIRECTORY_SEPARATOR . $id . ".txt"));
        }          
        else
        {
            $initial_basename = false;
        }




        if (file_exists($originals_dir . DIRECTORY_SEPARATOR . $id . ".png"))
        {
            if ($initial_basename)
            {
                return $current_directory . DIRECTORY_SEPARATOR . $initial_basename;
            }    
            else
            {
                //return $current_directory . DIRECTORY_SEPARATOR . pathinfo($current_path, PATHINFO_FILENAME) . ".png";
                return $current_directory . DIRECTORY_SEPARATOR . $this->mbFilename($current_path) . ".png";
            }
        }   
        else if (file_exists($originals_dir . DIRECTORY_SEPARATOR . $id . ".jpg"))
        {
            if ($initial_basename)
            {
                return $current_directory . DIRECTORY_SEPARATOR . $initial_basename;
            }    
            else
            {
                //return $current_directory . DIRECTORY_SEPARATOR . pathinfo($current_path, PATHINFO_FILENAME) . ".jpg";
                return $current_directory . DIRECTORY_SEPARATOR . $this->mbFilename($current_path) . ".jpg";            
            }

        }
        else
        {
            return $current_path;
        }

    }

    public function backup_original($attachment_id, $attachment_guid, $current_path, $originals_dir)
    {        
        $ret['out'] = "";


        if (file_exists($originals_dir . DIRECTORY_SEPARATOR . $attachment_id . ".png"))
        {
            $ret['original_path'] = $originals_dir . DIRECTORY_SEPARATOR . $attachment_id . ".png";
            $ret['out'] .= __("Backed up original image found at") . ": " . $ret['original_path'] . "\n";
            return $ret;

        }
        else if (file_exists($originals_dir  . DIRECTORY_SEPARATOR . $attachment_id . ".jpg"))
        {
            $ret['original_path'] = $originals_dir  . DIRECTORY_SEPARATOR . $attachment_id . ".jpg";
            $ret['out'] .= __("Backed up original image found at") . ": " . $ret['original_path'] . "\n";
            return $ret;
        }    

        if (! file_exists($current_path))
        {
            $ret['out'] .=  __("ERROR: Attachment file not found") . " " . $current_path  . "\n";
            $ret['error'] = true;
            return $ret;
        }  

        $current_filetype = $this->path_to_typeflag($current_path);     
        if (!($current_filetype === $this->PNG  ||  $current_filetype === $this->JPG))
        {
            $ret['out'] .=  __("Skip. Unsupported type") . " " .  $this->typeflag_to_ext($current_filetype) . "\n";
            $ret['error'] = true;        
            return $ret;
        }

        $ret['original_path'] = $originals_dir . DIRECTORY_SEPARATOR . $attachment_id . "." . $this->typeflag_to_ext($current_filetype);                        
        $ret['out'] .=  __("Creating original image backup to") . " " . $ret['original_path'] . "\n";

        $this->wait_and_unlink($ret['original_path']);
        copy($current_path, $ret['original_path']);    
        if (!file_exists($ret['original_path']))
        {
            $ret['out'] .=  "Copy " . $current_path . " to " . $ret['original_path'] . " faild\n";
            $ret['error'] = true;
            return $ret;       
        }

        $basename_file_path = $originals_dir . DIRECTORY_SEPARATOR . $attachment_id . "_basename.txt";
        $this->wait_and_unlink($basename_file_path);

        file_put_contents($basename_file_path, $this->mbBasename($current_path));    
        if (!file_exists($basename_file_path))
        {
            $ret['out'] .=  __("Faild to create basename file in") . " $basename_file_path\n";
            $ret['error'] = true;
            return $ret;       
        }

        $guid_file_path = $originals_dir . DIRECTORY_SEPARATOR . $attachment_id . "_guid.txt";
        $this->wait_and_unlink($guid_file_path);
        file_put_contents($guid_file_path, $attachment_guid);
        if (!file_exists($guid_file_path))
        {
            $ret['out'] .=  __("Faild to create Guid file in") . " $guid_file_path\n";
            $ret['error'] = true;
            return $ret;       
        }

        return $ret;
    }                


    public function path_filter_apply($initial_path, $path_filter)
    {
        global $wpmjgu_func;

        $ret['error'] = true;
        $ret['out'] = '';


        $path_filter_lines = mb_split("\n", $path_filter);

        foreach ($path_filter_lines as $path_filter_line)
        {
            if (( $path_filter_line = $wpmjgu_func->string->mbTrim($path_filter_line) ))
            {
                $path_filter_item = mb_split(',', $path_filter_line);
                if (count($path_filter_item) !== 2 )
                {
                    $ret['out'] .= 'Skip bad Path filter ' . $path_filter_line . "\n";
                }

                $pattern =  $wpmjgu_func->string->mbTrim($path_filter_item[0]);
                $quality =  $wpmjgu_func->string->mbTrim($path_filter_item[1]);

                if ($quality !== 'SKIP')
                {
                    if (! $wpmjgu_func->validate->is_exacting_int($quality)   ||  (int) $quality < 1   ||   (int) $quality > 100)
                    {
                        $ret['out'] .= 'Skip Path filter with wrong quality ' . $path_filter_line . "\n";
                    }                  
                }    

                $preg_pattern = $wpmjgu_func->string->mbPregQuote($pattern, '#');
                $preg_pattern = $wpmjgu_func->string->mbStrReplace('\*', '.*', $preg_pattern);
                $preg_pattern = $wpmjgu_func->string->mbStrReplace('\?', '.', $preg_pattern);

                if (preg_match( '#' . $preg_pattern . '#iu', $initial_path))
                {
                    $ret['out'] .= "Matches pattern $pattern\n";
                    if ($quality === 'SKIP')
                    {    
                        $ret['out'] .= "Pattern set to SKIP\n";
                    }
                    else
                    {
                        $ret['out'] .= "Seting quality to $quality\n";
                    }

                    $ret['quality'] = $quality;
                    $ret['error'] = false;
                    return $ret;
                }


            }        
        }    

        $ret['out'] .= "Not matches any Path filter. Will have the default quality\n";
        $ret['quality'] = 'DEFAULT';
        $ret['error'] = false;    
        return $ret;
    }        



    public function mime_to_typeflag($mime)
    {
        switch (strtolower($mime))
        {
            case "image/png":
                return $this->PNG;

            case "image/jpeg":
            case "image/jpg":            
                return $this->JPG;

            case "image/gif":            
                return $this->GIF;            

            case "image/webp":            
                return $this->WEBP; 

            default:
                return $this->TYPE_UNDEFINED;
        }
    }

    public function path_to_typeflag($path)
    {
        //$ext = pathinfo($path, PATHINFO_EXTENSION);
        $ext = $this->mbExt($path);

        switch (mb_strtolower($ext))
        {
            case "png":
                return $this->PNG;

            case "jpeg":
            case "jpg":            
                return $this->JPG;

            case "gif":            
                return $this->GIF;            

            case "webp":            
                return $this->WEBP;

            default:
                return $this->TYPE_UNDEFINED;
        }   

    }

    public function typeflag_to_ext($typeflag)
    {
        switch ($typeflag)
        {
            case $this->PNG:
                return 'png';

            case $this->JPG:
                return 'jpg';

            case $this->GIF:
                return 'gif';            

            case $this->WEBP:
                return 'webp';

            default:
                return 'undefined';
        }    
    }

    public function typeflag_to_mine($typeflag)
    {
        switch ($typeflag)
        {
            case $this->PNG:
                return "image/png";

            case $this->JPG:
                return "image/jpeg";

            case $this->GIF:
                return "image/gif"; 

            case $this->WEBP:
                return "image/webp";            

            default:
                return 'undefined';            
        }    
    }


    public function delete_attachment_thumbnails($currentPath)
    {
        global $wpmjgu_func;

        //$pathinfo = pathinfo($filename);
        //$directory_files = scandir($pathinfo['dirname']);
        $currentPathDirname = $this->mbDirname($currentPath);
        $currentPathFilename = $this->mbFilename($currentPath);
        $directory_files = scandir($currentPathDirname);
        
        
        //$thumbnails_pattern = '#^' . $wpmjgu_func->string->mbPregQuote($pathinfo['filename'], '#') . '\-\d+x\d+\.(jpg|jpeg|png)$#iu';                    
        $thumbnails_pattern = '#^' . $wpmjgu_func->string->mbPregQuote($currentPathFilename, '#') . '\-\d+x\d+\.(jpg|jpeg|png)$#u';                            
        
        $thumbnails_filenames = preg_grep($thumbnails_pattern, $directory_files);
        if (count($thumbnails_filenames) === 0) { return; }
        
        print "\n";
        foreach ($thumbnails_filenames as $thumbnail_filename)
        {
            $thumbnail_filename = $currentPathDirname . DIRECTORY_SEPARATOR . $thumbnail_filename;
            $this->wait_and_unlink($thumbnail_filename);             
            print "Thumbnail deleted " . $thumbnail_filename . "\n";
        }  
        print "\n";        
    }

    
    public function has_webp_duplicates($path)
    {
        global $wpmjgu_func;
        $duplicates = array();
        
        $path_dot_webp = $path . ".webp";
        if (file_exists($path_dot_webp)) { $duplicates[] = $path_dot_webp; }
        
        $dir = $this->mbDirname($path);
        $filename = $this->mbFilename($path);
     
        $directory_files = scandir($dir);
        $webp_thumbnails_pattern = '#^' . $wpmjgu_func->string->mbPregQuote($filename, '#') . '\-\d+x\d+\.(jpg|jpeg|png|gif)\.webp$#u';                    

        $webp_thumbnails_basenames = preg_grep($webp_thumbnails_pattern, $directory_files);        
        foreach ($webp_thumbnails_basenames as $thumbnail_basename)
        {
            $duplicates[] = $dir . "/" . $thumbnail_basename;
        }
        
        if (count($duplicates) === 0) { $duplicates = false; } 
        
        return $duplicates;
    }        

    
    public function delete_webp_duplicates($path)
    {
        $duplicates = $this->has_webp_duplicates($path);
        if ($duplicates === false) { return; }
        
        print "\n";
        foreach ($duplicates as $duplicate_path)
        {
            print "Webp duplicate deleted $duplicate_path\n";
            $this->wait_and_unlink($duplicate_path);
        }    
        print "\n";
        
    }        

    
    public function content_url_replace($url_replace_array)
    {
        global $wpdb, $ALL_WPMJGU_SETTINGS;
        $ret['out'] = "";
        $current_log = "";
        
        foreach ($url_replace_array as $find => $replace) {

            $ret['out'] .= 'Replacing url "' . $find . '" with "' . $replace . '" in potst content' . "\n";
            $current_log .= "$find,$replace\r\n";
            
            $query = $wpdb->prepare("UPDATE `{$wpdb->prefix}posts` SET `post_content` = REPLACE(`post_content`,\"%s\",\"%s\")", array($find, $replace));
            // Debug */ $ret['out'] .= $query . "\n\n";
            if ($wpdb->query($query) === false)
            {
                $ret['out'] .= "Query faild :" . $query . "\n";
                $ret['error'] = true;
            }    

            $query = $wpdb->prepare("UPDATE `{$wpdb->prefix}posts` SET `post_excerpt` = REPLACE(`post_excerpt`,\"%s\",\"%s\")", array($find, $replace));
            // Debug */ $ret['out'] .= $query . "\n\n";
            if ($wpdb->query($query) === false)
            {
                $ret['out'] .= "Query faild :" . $query . "\n";
                $ret['error'] = true;                
            }    

            $query = $wpdb->prepare("UPDATE `{$wpdb->prefix}postmeta` SET `meta_value` = REPLACE(`meta_value`,\"%s\",\"%s\")", array($find, $replace));
            // Debug */ $ret['out'] .= $query . "\n\n";
            if ($wpdb->query($query) === false)
            {
                $ret['out'] .= "Query faild :" . $query . "\n";
                $ret['error'] = true;                
            }    

        }  

        return $ret;
        
    }

    
    public function local_exec($command)
    {
        global $wpmjgu_func;        
        
        $ret['out'] = "";
        
        $curdir = exec("pwd");
        $curdir = $wpmjgu_func->string->mbTrim($curdir);
        //$curdir = rtrim($curdir, DIRECTORY_SEPARATOR);                
        $curdir = $wpmjgu_func->string->mbRTrim($curdir, '\/');        
        
        $stderr_temp_file_path = $curdir . DIRECTORY_SEPARATOR . rand(0,  getrandmax()) . "_stderr.txt";

        $retcode = "";
        $shellout = "" ;
        
        exec($command . " 2>\"$stderr_temp_file_path\"", $shellout, $retcode);
        $ret['out'] .= implode("\n", $shellout);

        if (file_exists($stderr_temp_file_path))
        {
            $ret['out'] .= file_get_contents($stderr_temp_file_path); 
        }
        
        if ($retcode)
        {        
            $ret['error'] = true;
        }

        $this->wait_and_unlink($stderr_temp_file_path);                     
        
        return $ret;
    }        

    
    public function wait_and_unlink($file_path, $max_wait = 300)
    {
        for ($t = 0; $t <= $max_wait * 1000000; $t += 200000)
        {
            @unlink($file_path);
            if (! file_exists($file_path)) { return true; }
            
            usleep(200000);
        }
        
        return false;
    }        

    
    public function write_blocking_htaccess($dir)
    {
        file_put_contents($dir . "/.htaccess", "Order deny,allow\nDeny from all");
    }        

    
    public function generate_salt($string_length)
    {
        $maxrand = getrandmax();
        $salt = "";
        
        do
        {
            $salt = $salt . rand(0,  $maxrand);
        }    
        while(strlen($salt) < $string_length);
        
        if (strlen($salt) > $string_length) { $salt = substr($salt, 0, $string_length); }
        
        return $salt;
            
    } 

    
    public function updateAttachmentGuit($attachmentId, $newGuid)
    {
        global $wpdb;
        
        $query = $wpdb->prepare("UPDATE wp_posts SET guid=%s WHERE ID=%d", array($newGuid, $attachmentId));
        return $wpdb->query($query);
    }        
    
    
    public function mbDirname($path) {
        $parts = mb_split("[/\\\\]", $path);
        $count = count($parts);
        if ($count > 1) {
            unset ($parts[$count - 1]);
        }
        
        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    
    public function mbBasename($path) {
        $parts = mb_split("[/\\\\]", $path);
        $count = count($parts);

        return $parts[$count - 1];
    }

    
    public function mbExt($path)
    {
        $baseName = $this->mbBasename($path);
        if (($pos = mb_strrpos($baseName, '.')) === false) {
            return '';
        } else {
            return mb_substr($baseName, $pos + 1);
        }
    }        

    
    public function mbFilename($path) {
        $baseName = $this->mbBasename($path);
        if (($pos = mb_strrpos($baseName, '.')) === false) {
            return $baseName;
        } else {
            return mb_substr($baseName, 0, $pos);
        }       
    }

    
    public function mbPathWithoutExt($path)
    {
        $baseName = $this->mbBasename($path);
        if (mb_strpos($baseName, '.') === false) {
            return $path;
        } else {
            $pos = mb_strrpos($path, '.');
            return mb_substr($path, 0, $pos);
        }
    }      
    
}



