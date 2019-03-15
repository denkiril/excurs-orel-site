<?php

/*  This code was written by Ihor Slyva
    http://ihorsl.com 
    License GPLv2 */

namespace wpmjgu;


class class_wpmjgu_validate
{

public function user_configuration($all_wpmjgu_settings)
{    
    if ( ! isset($all_wpmjgu_settings['wpmjgu_settings_validation_error']))
    {
        return 'The plugin needs configuration before running "Batch optimization". Go to the "Settings" tab, edit and save the settings.';
    }          

    if ( $all_wpmjgu_settings['wpmjgu_settings_validation_error'] === "true")
    {
        return 'There was an error during your last settings save. Please update your settings in the "Settings" tab.';
    }        
}    
    
public function working_directory($working_directory)
{
    global $wpmjgu_func;

    if ($working_directory === "") 
    {
        return "Plugin working directory not set";
    }
    
    if (file_exists($working_directory)  &&  !is_dir($working_directory)) {
        return $working_directory . " " . __("is not a directory");
    }
    
    if (! file_exists($working_directory))
    {
        if ($working_directory ===  $wpmjgu_func->url_file->PLUGIN_DEFAULT_WORKING_DIRECTORY) {    

            mkdir($working_directory);
            if (!file_exists($working_directory)) {
                return "Can't create plugin working directory " . $working_directory;
            }
            
        }
        else {
            return "Plugin working directory does not exist " . $working_directory;
        }         
    }    


    $write_test_file = $working_directory . DIRECTORY_SEPARATOR . "writetest.txt";
    @file_put_contents($write_test_file, "WRITE TEST FILE");
    if (! file_exists($write_test_file)) {
        return __("Plugin working directory is not writable") . " " . $working_directory;
    } else {
        unlink($write_test_file);
    }    
    
    $wpmjgu_func->url_file->write_blocking_htaccess($working_directory);

    //----

    $original_images_directory = $working_directory . "/" . $wpmjgu_func->url_file->ORIGINAL_IMAGES_SUBDIRECTORY;
    @mkdir($original_images_directory);
    $wpmjgu_func->url_file->write_blocking_htaccess($original_images_directory);

    $temp_directory = $working_directory . "/" . "temp";
    @mkdir($temp_directory);
    $wpmjgu_func->url_file->write_blocking_htaccess($temp_directory);   
    
}
    

public function default_jpeg_quality($mode, $quality)
{
    if ($mode === "mozjpeg_vs_webp") { return false; }
    
    if ($quality === ""  ||  !$this->is_exacting_int($quality)) 
    {
        return "Jpeg quality is empty or not a number";
    }    

    switch ($mode)
    {
       
        case 'mozjpeg':        
            if ($quality < 50 || $quality > 100)
            {
                return "Jpeg quality must be 50-100 for MozJpeg cjpeg";
            }            
        break;

        case 'webp_duplicate':        
            if ($quality < 50 || $quality > 100)
            {
                return "Jpeg quality must be 50-100 for cwebp";
            }            
        break;
        
        case 'guetzli':        
            if ($quality < 84 || $quality > 100)
            {
                return "Jpeg quality must be 84-100 for Google Guetzli";
            }            
        break;
        
        default:
            return "Unknown encoder " . $mode;
    }    
}        

public function jpeg_compression_min_benefit($benefit)
{

    
    if ($benefit === ""  ||  !$this->is_exacting_int($benefit)) 
    {
        return "Minimal benefit from jpeg compression is empty or not a number";
    }   

    if ( $benefit <= 0  ||  $benefit >= 100 )
    {
        return 'Minimal benefit from jpeg compression must be from 0 to 100';
    }
}        


public function ssh_server($sshserver)
{
    if ($sshserver === "")
    {
        return "SSH Server not set";
    }    
}        

public function ssh_port($sshport)
{
    if ($sshport === "")
    {
        return "SSH Port not set";
    }    
    
    if (!$this->is_exacting_int($sshport)   ||  $sshport < 1   ||   $sshport > 0xffff)
    {
        return "SSH Port must be 1-65535";
    }    
} 

public function ssh_username($ssh_username)
{
    if ($ssh_username === "")
    {
        return "SSH Username not set";
    }    
} 

public function ssh_password($ssh_password)
{
    if ($ssh_password === "")
    {
        return "SSH Password not set";
    }    
} 

public function mozjpeg_encoder_directory_local($mozjpeg_encoder_directory)
{
    $cjpeg = $mozjpeg_encoder_directory . DIRECTORY_SEPARATOR . "cjpeg";
    if (!file_exists($cjpeg)) { return "cjpeg not found in " . $mozjpeg_encoder_directory; }
    $cjpeg_version = exec($cjpeg . " -version 2>&1");
    if (stripos($cjpeg_version, 'mozjpeg version') === false) { return "cjpeg is not mozjpeg"; } 
    

    $jpegtran = $mozjpeg_encoder_directory . DIRECTORY_SEPARATOR . "jpegtran";
    if (!file_exists($jpegtran)) { return "jpegtran not found in " . $mozjpeg_encoder_directory; }            
    $jpegtran_version = exec($jpegtran . " -version 2>&1");
    if (stripos($jpegtran_version, 'mozjpeg version') === false) { return "jpegtran is not mozjpeg"; } 
    
}        

public function mozjpeg_encoder_directory_ssh($sshc, $mozjpeg_encoder_directory)
{        
    $cjpeg = $mozjpeg_encoder_directory . "/cjpeg";
    $cjpeg_version = $sshc->exec($cjpeg . " -version");
    if (stripos($cjpeg_version, 'mozjpeg version') === false) { return "Ssh server error: cjpeg not found in $mozjpeg_encoder_directory or is not the mozjpeg"; } 

         
    $jpegtran = $mozjpeg_encoder_directory . "/jpegtran";
    $jpegtran_version = $sshc->exec($jpegtran . " -version");
    if (stripos($jpegtran_version, 'mozjpeg version') === false) { return "Ssh server error: jpegtran not found in $mozjpeg_encoder_directory or is not the mozjpeg"; } 
}

public function guetzli_encoder_directory_local($guetzli_encoder_directory)
{
    $guetzli = $guetzli_encoder_directory . DIRECTORY_SEPARATOR . "guetzli";
    if (!file_exists($guetzli)) { return "guetzli not found in " . $guetzli_encoder_directory; }
}

public function guetzli_encoder_directory_ssh($sshc, $guetzli_encoder_directory)
{
    $guetzli = $guetzli_encoder_directory  . "/guetzli";
    $guetzli_version = $sshc->exec($guetzli);
    if (strpos($guetzli_version, 'Guetzli JPEG compressor') === false) { return "Ssh server error: guetzli not found in $guetzli_encoder_directory"; }     
}        


public function webp_encoder_directory_local($wepb_encoder_directory)
{
    $cwebp = $wepb_encoder_directory . DIRECTORY_SEPARATOR . "cwebp";
    if (!file_exists($cwebp)) { return "cwebp not found in " . $wepb_encoder_directory; }
}

public function webp_encoder_directory_ssh($sshc, $webp_encoder_directory)
{
    $cwebp = $webp_encoder_directory . "/cwebp";
    $cwebp_version = $sshc->exec($cwebp);
    if (stripos($cwebp_version, 'Try -longhelp for an exhaustive list of advanced options') === false) { return "cwebp not found in " . $webp_encoder_directory; }         
} 

public function required_encoders_directories_local($all_wpmjgu_settings)
{
    switch ($all_wpmjgu_settings["wpmjgu_mode"])
    {
        
        case 'mozjpeg_vs_webp':
            $error = $this->webp_encoder_directory_local($all_wpmjgu_settings['wpmjgu_webp_encoder_directory']);
            if ($error) { return $error; }
        case 'mozjpeg':
            return $this->mozjpeg_encoder_directory_local($all_wpmjgu_settings['wpmjgu_mozjpeg_encoder_directory']);
        break; 

        case 'webp_duplicate':
            return $this->webp_encoder_directory_local($all_wpmjgu_settings['wpmjgu_webp_encoder_directory']);
        break;
    
        case 'guetzli':
            return $this->guetzli_encoder_directory_local($all_wpmjgu_settings['wpmjgu_guetzli_encoder_directory']);
        break;
    
        default:
            return "Unknown mode " . $all_wpmjgu_settings["wpmjgu_mode"] . " in function validate->required_encoders_directories_local";
    }
}        

public function required_encoders_directories_ssh($sshc, $all_wpmjgu_settings)
{
    switch ($all_wpmjgu_settings["wpmjgu_mode"])
    {
        case 'mozjpeg_vs_webp':
            $error = $this->webp_encoder_directory_ssh($sshc, $all_wpmjgu_settings['wpmjgu_webp_encoder_directory']);
            if ($error) { return $error; }
        case 'mozjpeg':
            return $this->mozjpeg_encoder_directory_ssh($sshc, $all_wpmjgu_settings['wpmjgu_mozjpeg_encoder_directory']);
        break;    

        case 'webp_duplicate':
            return $this->webp_encoder_directory_ssh($sshc, $all_wpmjgu_settings['wpmjgu_webp_encoder_directory']);
        break;
            
        case 'guetzli':
            return $this->guetzli_encoder_directory_ssh($sshc, $all_wpmjgu_settings['wpmjgu_guetzli_encoder_directory']);
        break;       
    
        default:
            return "Unknown encoder " . $all_wpmjgu_settings["wpmjgu_mode"] . " in function validate->required_encoders_directories_ssh";    
    
    }
}        


    public function ssh($all_wpmjgu_settings)
    {

        $ssh_working_directory = $all_wpmjgu_settings['wpmjgu_ssh_working_directory'];

        global $wpmjgu_func;   
        //$start_utime = microtime(true);

        $sshc = new \phpseclib\Net\SSH2($all_wpmjgu_settings['wpmjgu_ssh_server'], $all_wpmjgu_settings['wpmjgu_ssh_port']);
        if (! @$sshc->login($all_wpmjgu_settings['wpmjgu_ssh_username'],  $all_wpmjgu_settings['wpmjgu_ssh_password']))
        {
            return "Can`t connect to ssh server or bad login";
        }        

        if (( $error = $this->required_encoders_directories_ssh($sshc, $all_wpmjgu_settings) ))
        {
            return $error;
        }

        unset($sshc);



        $sftp = new \phpseclib\Net\SFTP($all_wpmjgu_settings['wpmjgu_ssh_server'], $all_wpmjgu_settings['wpmjgu_ssh_port']);
        if (! @$sftp->login($all_wpmjgu_settings['wpmjgu_ssh_username'],  $all_wpmjgu_settings['wpmjgu_ssh_password']))
        {
            return "Can`t connect to sftp server";
        }       

        if (! $ssh_working_directory)
        {
            $ssh_working_directory = $sftp->pwd();
        }    

        if (! $sftp->file_exists($ssh_working_directory))
        {
            return "Ssh server error: Ssh server working directory not exists $ssh_working_directory";
        }  

        if (! $sftp->chdir($ssh_working_directory))
        {
            return "Ssh server error: Can't change directory to $ssh_working_directory";
        }    

        if (! $sftp->put('wpmjgu_test_upload_file', 'test'))
        {
            return "Ssh server error: Can't upload test file to $ssh_working_directory";
        }        

        if (! $sftp->delete('wpmjgu_test_upload_file', 'test'))
        {
            return "Ssh server error: Can't delete test file in $ssh_working_directory";
        }            


    }        


    public function freeOnlineServer($all_wpmjgu_settings)
    {
        if (strpos($all_wpmjgu_settings['wpmjgu_ssh_server'], 'ihorsl.com') === false) {
            return null;
        }

        if (
            isset($all_wpmjgu_settings['wpmjgu_batch_optimization_intensive_resources_consumption_mode']) 
            && $all_wpmjgu_settings['wpmjgu_batch_optimization_intensive_resources_consumption_mode']
            && $all_wpmjgu_settings['wpmjgu_jpeg_encoder_location'] === 'remote'
        ) {
            return 'Free Online Server does not support "Intensive resources consumption mode"';
        }
        
        if ($all_wpmjgu_settings['wpmjgu_mode'] === 'guetzli') {
            return "Free Online Server does not support Google Guetzli. Use plugin's virtual machine for Google Guetzli";
        }
        

    }        

    public function batchOptimization($all_wpmjgu_settings)
    {
        global $wpmjgu_func;

        if (( $error = $this->user_configuration($all_wpmjgu_settings) ))
        {
            return $error;
        }   

        if (( $error = $this->working_directory($all_wpmjgu_settings['wpmjgu_plugin_working_directory']) ))
        {
            return $error;
        }        

        if (( $error = $this->default_jpeg_quality($all_wpmjgu_settings['wpmjgu_mode'], $all_wpmjgu_settings['wpmjgu_default_jpeg_quality']) ))
        {
            return $error;
        }  

        if (( $error = $this->jpeg_compression_min_benefit($all_wpmjgu_settings['wpmjgu_jpeg_compression_min_benefit']) ))
        {
            return $error;
        }     

        if (( $error = $this->freeOnlineServer($all_wpmjgu_settings) ))
        {
            return $error;
        }  

        if ($all_wpmjgu_settings['wpmjgu_jpeg_encoder_location'] === 'local')
        {
            if (( $error = $this->required_encoders_directories_local($all_wpmjgu_settings) ))
            {
                return $error;
            }      
        }
        else
        {
            if (( $error = $this->ssh($all_wpmjgu_settings) ))
            {
                return $error;
            }              
        }

    }        

    public function batchRevert($all_wpmjgu_settings)
    {
        
        if (( $error = $this->user_configuration($all_wpmjgu_settings) ))
        {
            return $error;
        }

        if (( $error = $this->working_directory($all_wpmjgu_settings['wpmjgu_plugin_working_directory']) ))
        {
            return $error;
        }    

    }        

    public function basis() 
    {
        
        if (!function_exists('imagecreatefromjpeg')  ||  !function_exists('imagecreatefrompng')  ||  !function_exists('imagecreatefromgif'))
        {
            return 'This plugin requires PHP GD library. Read more <a href="http://php.net/manual/en/image.installation.php" target="_blank">here<a>';
        }       
        
        if (!function_exists('mb_check_encoding') )
        {
            return 'This plugin requires "PHP Multibyte String" library. Read more <a href="http://php.net/manual/en/mbstring.installation.php" target="_blank">here<a>';
        }           
    }





    public function is_exacting_int($var)
    {
        $var = trim((string) $var);
        $var = ltrim($var, "-");

        if (preg_match("/[^0-9]/", $var))
        {
            return false;
        }
        else
        {
            return true;
        }
    }        

}

