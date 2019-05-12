<?php

/*  This code was written by Ihor Slyva
    http://ihorsl.com 
    License GPLv2 */


namespace wpmjgu;


class class_wpmjgu_image
{

    private $sshc = null;
    private $sftp = null;
    
    public function get_thumbnails_sizes()
    {
	global $_wp_additional_image_sizes;

	$sizes = array();

	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}

	return $sizes;
    }

public function wp_image_resize_crop($orig_gd, $dest_max_w, $dest_max_h, $crop = false, $with_alpha = false)
{
    $gd_from_filter = apply_filters('wpmjgu_gd_resize_crop', $orig_gd, $dest_max_w, $dest_max_h, $crop, $with_alpha);
    if ($gd_from_filter !== $orig_gd) {
        $gd_from_filter = apply_filters('wpmjgu_gd_after_resize', $gd_from_filter);
        return $gd_from_filter;
    }   
    
    $orig_w = imagesx($orig_gd);
    $orig_h = imagesy($orig_gd);    
    $dimensions = image_resize_dimensions( $orig_w, $orig_h, $dest_max_w, $dest_max_h, $crop);
    
    if (is_array($dimensions))
    {
        // int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
        $dest_w = $dimensions[4];
        $dest_h = $dimensions[5];

        $dest_gd = imagecreatetruecolor($dest_w, $dest_h);
        
        if ($with_alpha) {
            imagealphablending($dest_gd, false);
            imagesavealpha($dest_gd, true);            
        } else if ( function_exists( 'imageantialias' )) {
            imageantialias($dest_gd, true );
        }        
        
        imagecopyresampled($dest_gd, $orig_gd, $dimensions[0], $dimensions[1], $dimensions[2], $dimensions[3], $dimensions[4], $dimensions[5], $dimensions[6], $dimensions[7]);

        $dest_gd = apply_filters('wpmjgu_gd_after_resize', $dest_gd);
        
        return $dest_gd;
    }
    else
    {
        return null;
    }
            
}        
    
    
    public function does_image_has_alpha($imgdata)
    {
        $w = imagesx($imgdata);
        $h = imagesy($imgdata);
        
        $max_test_size = 200;
        
        //scale the image to save processing time
        if( ($w > $max_test_size || $h > $max_test_size)  ) {
            $scaled_gd = imagecreatetruecolor($max_test_size, $max_test_size);
            imagealphablending($scaled_gd, FALSE);
            imagecopyresampled($scaled_gd, $imgdata, 0, 0, 0, 0, $max_test_size, $max_test_size, $w, $h );
            $imgdata = $scaled_gd;
            $w = imagesx($imgdata);
            $h = imagesy($imgdata);
        }
        
        //run through pixels until transparent pixel is found:
        for($i = 0; $i < $w; $i++) {
            for($j = 0; $j < $h; $j++) {
                $ci = imagecolorat($imgdata, $i, $j);
                $rgba = imagecolorsforindex($imgdata, $ci);
                if($rgba['alpha']) { return true; }
            }
        }
        
        return false;
    }


    
    
    public function jpeg_compress_wrapper($source_file_path, $dest_file_path, $lossless_optimize_only = false)
    {
        // USES constants $ALL_WPMJGU_SETTINGS, WPMJGU_CURRENT_JPEG_QUALITY
        
        global $wpmjgu_func, $ALL_WPMJGU_SETTINGS;
        $ret['out'] = "";
        
        if ($ALL_WPMJGU_SETTINGS['wpmjgu_mode'] === 'mozjpeg_vs_webp')
        {
            return $this->jpeg_compress_mozjpeg_vs_webp($source_file_path, $dest_file_path, WPMJGU_CURRENT_JPEG_QUALITY, $lossless_optimize_only); 
        }
        else
        {
            return $this->jpeg_compress_constant_quality($source_file_path, $dest_file_path, $ALL_WPMJGU_SETTINGS['wpmjgu_mode'], WPMJGU_CURRENT_JPEG_QUALITY, $lossless_optimize_only); 
        }    
    }   
    
    
    
    

    public function jpeg_compress_constant_quality($source_file_path, $dest_file_path, $jpeg_encoder, $current_jpeg_quality, $lossless_optimize_only = false)
    {
        global $wpmjgu_func, $ALL_WPMJGU_SETTINGS;
        $ret['out'] = "";

        if ($current_jpeg_quality === 'DEFAULT')
        {
            $current_jpeg_quality = (int) $ALL_WPMJGU_SETTINGS['wpmjgu_default_jpeg_quality'];
        }
            
        if ($ALL_WPMJGU_SETTINGS['wpmjgu_jpeg_encoder_location'] === 'local')
        {
            $wpmjgu_func->url_file->wait_and_unlink($dest_file_path);

            //--------------------------
            
            switch ($jpeg_encoder)
            {
                case 'mozjpeg':
                    if ($lossless_optimize_only)
                    {    
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_mozjpeg_encoder_directory'] . "/jpegtran -optimize \"$source_file_path\" > \"$dest_file_path\"";
                    }
                    else
                    {
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_mozjpeg_encoder_directory'] . "/cjpeg -optimize -quality " . $current_jpeg_quality . " \"$source_file_path\" > \"$dest_file_path\"";
                    }
                break;    

                case 'guetzli':
                    if ($lossless_optimize_only)
                    {    
                        $ret['not_supported'] = true;
                        return $ret;
                    }
                    else
                    {
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_guetzli_encoder_directory'] . "/guetzli --nomemlimit --quality " . $current_jpeg_quality . " \"$source_file_path\" \"$dest_file_path\"";
                    }
                break;                  

                case 'webp':
                    if ($lossless_optimize_only)
                    {    
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_webp_encoder_directory'] . "/cwebp -lossless -q 100 \"$source_file_path\" -o \"$dest_file_path\"";
                    }
                    else
                    {
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_webp_encoder_directory'] . "/cwebp -q " . $current_jpeg_quality . " \"$source_file_path\" -o \"$dest_file_path\"";
                    }
                break;  

                case 'gif2webp':
                    if ($lossless_optimize_only)
                    {    
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_webp_encoder_directory'] . "/gif2webp -q 100 \"$source_file_path\" -o \"$dest_file_path\"";
                    }
                    else
                    {
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_webp_encoder_directory'] . "/gif2webp -lossy -q " . $current_jpeg_quality . " \"$source_file_path\" -o \"$dest_file_path\"";
                    }
                break; 
                
                default:
                    $ret['out'] .= "function jpeg_compress_constant_quality doesn't support jpeg encoder \"$jpeg_encoder\"\n";
                    $ret['error'] = true;
                    return $ret;
            }
            
            
            //--------------------------
            
            $r = $wpmjgu_func->url_file->local_exec($command);
            if (isset($r['error'])  ||   !file_exists($dest_file_path))
            {
                $ret['out'] .= $command . "\n";            
                $ret['out'] .= $r['out'];
                $ret['error'] = true;
            }   
            
            return $ret;
        }    
        else if ($ALL_WPMJGU_SETTINGS['wpmjgu_jpeg_encoder_location'] === 'remote')        
        {
            $sshMaxConnectionAttempts = 5;
            $sshConnectionAttemptDeley = 60;
            
            
            if (! $this->sshc)
            {    
                $this->sshc = new \phpseclib\Net\SSH2($ALL_WPMJGU_SETTINGS['wpmjgu_ssh_server'], $ALL_WPMJGU_SETTINGS['wpmjgu_ssh_port']);

                $attempt = 1;                
                while (! @$this->sshc->login($ALL_WPMJGU_SETTINGS['wpmjgu_ssh_username'], $ALL_WPMJGU_SETTINGS['wpmjgu_ssh_password'] ))
                {
                    if ($attempt > $sshMaxConnectionAttempts) {
                        $ret['out'] .= __("Faild to connect to ssh server or bad login") . "\n";
                        $ret['error'] = true;
                        return $ret;
                    } else {
                        $ret['out'] .= sprintf( __("Can`t connect to ssh server. Retry in %s seconds"), $sshConnectionAttemptDeley) . "\n";                        
                        sleep($sshConnectionAttemptDeley);
                        $attempt++;
                    }                    
                }         
            }
            
            if (! $this->sftp)
            {    
                $this->sftp = new \phpseclib\Net\SFTP($ALL_WPMJGU_SETTINGS['wpmjgu_ssh_server'], $ALL_WPMJGU_SETTINGS['wpmjgu_ssh_port']);
                
                $attempt = 1;
                while (! @$this->sftp->login($ALL_WPMJGU_SETTINGS['wpmjgu_ssh_username'],  $ALL_WPMJGU_SETTINGS['wpmjgu_ssh_password']))
                {
                    if ($attempt > $sshMaxConnectionAttempts) {
                        $ret['out'] .= __("Faild to connect to sftp server") . "\n";
                        $ret['error'] = true;
                        return $ret;
                    } else {
                        $ret['out'] .= sprintf( __("Can`t connect to sftp server. Retry in %s seconds"), $sshConnectionAttemptDeley) . "\n";
                        sleep($sshConnectionAttemptDeley);
                        $attempt++;
                    }
                }          
            }
                
            $ssh_working_directory = $ALL_WPMJGU_SETTINGS['wpmjgu_ssh_working_directory'];
            if (! $ssh_working_directory)
            {
                $ssh_working_directory = $this->sftp->pwd();
            }            

            $rand = $wpmjgu_func->url_file->generate_salt(32);
            $source_file_ssh_path = $ssh_working_directory . "/wpmjgu_" . $rand . "_in."  . pathinfo($source_file_path, PATHINFO_EXTENSION);
            $dest_file_ssh_path = $ssh_working_directory . "/wpmjgu_" . $rand . "_out."  . pathinfo($dest_file_path, PATHINFO_EXTENSION);


            if (! $this->sftp->put($source_file_ssh_path, $source_file_path, \phpseclib\Net\SFTP::SOURCE_LOCAL_FILE))
            {
                $ret['out'] .= "Ssh server error: Can't upload file to $ssh_working_directory\n";
                $ret['error'] = true;
                return $ret;                 
            }         

            //--------------------------

            switch ($jpeg_encoder)
            {
                case 'mozjpeg':
                    if ($lossless_optimize_only)
                    {    
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_mozjpeg_encoder_directory'] . "/jpegtran -optimize \"$source_file_ssh_path\" > \"$dest_file_ssh_path\"";
                    }
                    else
                    {
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_mozjpeg_encoder_directory'] . "/cjpeg -optimize -quality " . $current_jpeg_quality . " \"$source_file_ssh_path\" > \"$dest_file_ssh_path\"";
                    }
                break;    

                case 'guetzli':
                    if ($lossless_optimize_only)
                    {    
                        $ret['not_supported'] = true;
                        return $ret;
                    }
                    else
                    {
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_guetzli_encoder_directory'] . "/guetzli --quality " . $current_jpeg_quality . " \"$source_file_ssh_path\" \"$dest_file_ssh_path\"";
                    }
                break; 

                case 'webp':
                    if ($lossless_optimize_only)
                    {    
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_webp_encoder_directory'] . "/cwebp -lossless -q 100 \"$source_file_ssh_path\" -o \"$dest_file_ssh_path\"";
                    }
                    else
                    {
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_webp_encoder_directory'] . "/cwebp -q " . $current_jpeg_quality . " \"$source_file_ssh_path\" -o \"$dest_file_ssh_path\"";
                    }
                break; 
                
                case 'gif2webp':
                    if ($lossless_optimize_only)
                    {    
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_webp_encoder_directory'] . "/gif2webp -q 100 \"$source_file_ssh_path\" -o \"$dest_file_ssh_path\"";
                    }
                    else
                    {
                        $command = $ALL_WPMJGU_SETTINGS['wpmjgu_webp_encoder_directory'] . "/gif2webp -lossy -q " . $current_jpeg_quality . " \"$source_file_ssh_path\" -o \"$dest_file_ssh_path\"";
                    }
                break;                 
                
                default:
                    $ret['out'] .= "function jpeg_compress_constant_quality doesn't support jpeg encoder \"$jpeg_encoder\"\n";
                    $ret['error'] = true;
                    return $ret;
            }
            
            
            
            //--------------------------
            
            $this->sshc->disableQuietMode();
            $this->sshc->setTimeout(3600);
            $out = $this->sshc->exec($command);

            if ( $this->sshc->getExitStatus() )
            {
                $ret['out'] .= $command . "\n";
                $ret['out'] .= $out . "\n";            
                $ret['out'] .= "Ssh server error: Encoder error";
                $ret['error'] = true;
                return $ret;                
            }

            if ($this->sshc->isTimeout())
            {
                $ret['out'] .= "Ssh server error: Encoder timeout\n";
                $ret['error'] = true;
                return $ret;                 
            }               
            
            if (! $this->sftp->delete($source_file_ssh_path))
            {
                $ret['out'] .= "Ssh server error: Can't delete file $source_file_ssh_path\n";
                $ret['error'] = true;
                return $ret;            
            }  

            if (! $this->sftp->file_exists($dest_file_ssh_path))
            {
                sleep(15);
                if (! $this->sftp->file_exists($dest_file_ssh_path))
                {
                    $ret['out'] .= "Ssh server error: Output file $dest_file_ssh_path not created\n";
                    $ret['error'] = true;
                    return $ret;            
                }            
            }
            
            if (! $this->sftp->get($dest_file_ssh_path, $dest_file_path))
            {
                $ret['out'] .= "Ssh server error: Can't download file $dest_file_ssh_path\n";
                $ret['error'] = true;
                return $ret;                 
            }          

            if (! $this->sftp->delete($dest_file_ssh_path))
            {
                $ret['out'] .= "Ssh server error: Can't delete file $dest_file_ssh_path\n";
                $ret['error'] = true;
                return $ret;            
            }         

            return $ret;            
        }    
    }

    public function jpeg_compress_mozjpeg_vs_webp($source_file_path, $dest_file_path, $current_jpeg_quality,  $lossless_optimize_only)
    {        
        global $wpmjgu_func;
        $ret['out'] = "";
        
        if ($lossless_optimize_only)
        {    
            return $this->jpeg_compress_constant_quality($source_file_path, $dest_file_path, 'mozjpeg', $current_jpeg_quality, true); 
        }

        if ($current_jpeg_quality !== 'DEFAULT')
        {
            $r = $this->jpeg_compress_constant_quality($source_file_path, $dest_file_path, 'mozjpeg', $current_jpeg_quality, $lossless_optimize_only); 
            $r['out'] = "Path filter constrains constant jpeg quality $current_jpeg_quality\n" . $r['out'];
            return $r;
        } 

        $webp_path = $dest_file_path . ".webp";        
        $r = $this->jpeg_compress_constant_quality($source_file_path, $webp_path, 'webp', 75); 
        if (isset($r['error'])) { return $r; }
        
        $webp_size = round(filesize($webp_path) * 1.10);
        $ret['out'] .= "WebP image has $webp_size bytes\n";
        
        $mozjpeg_maxquality = 85;
        $mozjpeg_minquality = 55;
        $mozjpeg_quality_step = 5;

        $r = $this->jpeg_compress_constant_quality($source_file_path, $dest_file_path, 'mozjpeg', $mozjpeg_minquality); 
        if (isset($r['error']))
        {
            $wpmjgu_func->url_file->wait_and_unlink($webp_path);
            return $r;
        }
        
        if (filesize($dest_file_path) >= $webp_size)
        {
            $ret['out'] .= "mozjpeg_vs_webp chose minimal possible quality " . $mozjpeg_minquality . " for mozjpeg. Resulting jpeg file will be larger then webp \n";        
            $wpmjgu_func->url_file->wait_and_unlink($webp_path);            
            return $ret;            
        }    
        
        $loop_temp_file_path = $dest_file_path . "_loop_temp.jpg";
        for ($quality = $mozjpeg_minquality + $mozjpeg_quality_step; $quality <= $mozjpeg_maxquality; $quality += $mozjpeg_quality_step )
        {
            $r = $this->jpeg_compress_constant_quality($source_file_path, $loop_temp_file_path, 'mozjpeg', $quality); 
            if (isset($r['error']))
            {
                $wpmjgu_func->url_file->wait_and_unlink($webp_path);
                $wpmjgu_func->url_file->wait_and_unlink($loop_temp_file_path);                
                return $r;
            }

            if (filesize($loop_temp_file_path) > $webp_size)
            {
                $ret['out'] .= "mozjpeg_vs_webp algorithm chose quality " . ($quality - $mozjpeg_quality_step) .  " for mozjpeg\n";
                $wpmjgu_func->url_file->wait_and_unlink($webp_path);
                $wpmjgu_func->url_file->wait_and_unlink($loop_temp_file_path); 
                return $ret;                
            }
            else
            {
                rename($loop_temp_file_path, $dest_file_path);
            }
            
        }
        
        $ret['out'] .= "mozjpeg_vs_webp algorithm chose quality $quality for mozjpeg\n";
        $wpmjgu_func->url_file->wait_and_unlink($webp_path);
        return $ret; 
    }

    /*
        classWpmjguOptimizeThumbnail($attachmentId, $thumbnailSize)
        does everything himself, including db update
        
     *  thumbnail_compress_or_optimize($backedupImagePath, $basename, $destinationFolder, $maxWidth,  $maxHeight, $crop, $allWpmjguSettings)
        thumbnail_webp_duplicate($backedupImagePath, $currentThumbnailPath, $maxWidth,  $maxHeight, $crop, $allWpmjguSettings)
    
    */
        
}      



