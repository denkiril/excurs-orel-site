<?php

/**
Plugin Name: Opti MozJpeg Guetzli WebP
Plugin URI: https://ihorsl.com/en/wordpress-opti-mozjpeg-guetzli-webp/home/
Version: 1.16
Author: Ihor Slyva
Author uri: https://ihorsl.com
Description: Optimize your Wordpress images using this plugin and Mozilla MozJpeg, Google Guetzli or Google WebP encoders
*/

/*  This code was written by Ihor Slyva
    https://ihorsl.com 
    License GPLv2 */



if (!class_exists("\Composer\Autoload\ClassLoader"))
{    
    require __DIR__ .  "/other_includes/ClassLoader.php";
}

function wpmjgu_init_classes()
{
    global $wpmjgu_func;
    if (! isset($wpmjgu_func))
    {

        $classloader = new \Composer\Autoload\ClassLoader();
        $classloader->addPsr4('phpseclib\\', __DIR__ . DIRECTORY_SEPARATOR . 'phpseclib');
        $classloader->addPsr4('wpmjgu\\', __DIR__ );
        $classloader->register();        

        $wpmjgu_func = new \wpmjgu\class_wpmjgu_func();
        $wpmjgu_func->loadChildren();
    }    
}






function wpmjgu_admin()
{

    global $wpmjgu_func;    

    ?>
<style>
    <?php
        //print file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR .  "style.css");
        print file_get_contents($wpmjgu_func->url_file->mbDirname(__FILE__) . DIRECTORY_SEPARATOR .  "style.css");
    ?>
    
</style>

<div class="wrap">
<div id="icon-options-general" class="icon32"></div>
<h1 id="wpmjgu-title">Opti MozJpeg Guetzli WebP (v1.16)</h1>


    <?php

    if (($error = $wpmjgu_func->validate->basis()))
    {
        die('<br>ERROR: ' . $error);
    }        
    
    $active_tab = "settings";
    if(isset($_GET["tab"]))
    {
        $active_tab = $_GET["tab"];
    }

    ?>

<h2 class="nav-tab-wrapper">
    <a href="?page=<?php print $wpmjgu_func->url_file->PLUGIN_SLUG; ?>&tab=settings" class="nav-tab <?php if($active_tab == 'settings'){echo 'nav-tab-active';} ?> ">Settings</a>
    <a href="?page=<?php print $wpmjgu_func->url_file->PLUGIN_SLUG; ?>&tab=batch_optimization" class="nav-tab <?php if($active_tab == 'batch_optimization'){echo 'nav-tab-active';} ?>">Batch optimization</a>
    <a href="?page=<?php print $wpmjgu_func->url_file->PLUGIN_SLUG; ?>&tab=batch_revert" class="nav-tab <?php if($active_tab == 'batch_revert'){echo 'nav-tab-active';} ?>">Revert everything</a>    
    <a href="?page=<?php print $wpmjgu_func->url_file->PLUGIN_SLUG; ?>&tab=readme" class="nav-tab <?php if($active_tab == 'readme'){echo 'nav-tab-active';} ?>">Read me</a>        
</h2>
       
    <?php
    if ($active_tab === "settings")
    {
        global $wpmjgu_settings_dialog;
        $wpmjgu_settings_dialog->the_html();
    }
    elseif ($active_tab === "batch_optimization")
    {
        $wpmjgu_batch_optimization_dialog = new \wpmjgu\class_wpmjgu_batch_optimization_dialog();
        $wpmjgu_batch_optimization_dialog->the_html();
    }
    elseif ($active_tab === "batch_revert")
    {
        $wpmjgu_batch_revert_dialog = new \wpmjgu\class_wpmjgu_batch_revert_dialog();
        $wpmjgu_batch_revert_dialog->the_html();
    }  
    elseif ($active_tab === "readme")
    {
        $wpmjgu_readme_dialog = new \wpmjgu\class_wpmjgu_readme_dialog();
        $wpmjgu_readme_dialog->the_html();
    }    
    
}



    
function wpmjgu_display_options()
{

    wpmjgu_init_classes();
    global $wpmjgu_func;
    
    global $wpmjgu_settings_dialog;
    $wpmjgu_settings_dialog = new \wpmjgu\class_wpmjgu_settings_dialog();   

    //global $wpmjgu_func;
    //$wpmjgu_func->unset_all_settings();
    
    add_settings_section("wpmjgu_settins_section", "", array($wpmjgu_settings_dialog, 'settins_section_content'), $wpmjgu_func->url_file->PLUGIN_SLUG);
    
    add_settings_field("wpmjgu_plugin_working_directory", "Plugin working directory", array($wpmjgu_settings_dialog, "plugin_working_directory_show"), $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section");
    register_setting("wpmjgu_settins_section", "wpmjgu_plugin_working_directory",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "plugin_working_directory_check") ));    

    add_settings_field("wpmjgu_mode", "Mode", array($wpmjgu_settings_dialog, "mode_show"), $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section");
    register_setting("wpmjgu_settins_section", "wpmjgu_mode",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "mode_check") ));        

    add_settings_field("wpmjgu_path_filter", "Attachments' Path filter <a href='https://ihorsl.com/en/wordpress-opti-mozjpeg-guetzli-webp/frequently-asked-questions-about-opti-mozjpeg-guetzli-webp/#pathfilter-specified-folders' title='More about Path filters' target='_blank' class='help-link'></a>", array($wpmjgu_settings_dialog, "path_filter_show"), $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section");
    register_setting("wpmjgu_settins_section", "wpmjgu_path_filter",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "path_filter_check") ));                
    
    add_settings_field("wpmjgu_default_jpeg_quality", "Default quality", array($wpmjgu_settings_dialog, "default_jpeg_quality_show"), $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section");
    register_setting("wpmjgu_settins_section", "wpmjgu_default_jpeg_quality",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "default_jpeg_quality_check") ));            
    
    add_settings_field("wpmjgu_jpeg_compression_min_benefit", "Minimal benefit from lossy compression, percentage", array($wpmjgu_settings_dialog, "jpeg_compression_min_benefit_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section");
    register_setting("wpmjgu_settins_section", "wpmjgu_jpeg_compression_min_benefit",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "jpeg_compression_min_benefit_check") ));            
    
    add_settings_field("wpmjgu_jpeg_encoder_location", "Encoders location", array($wpmjgu_settings_dialog, "jpeg_encoder_location_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section");
    register_setting("wpmjgu_settins_section", "wpmjgu_jpeg_encoder_location",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "jpeg_encoder_location_check") ));            
    
    add_settings_field("wpmjgu_ssh_server", "SSH Server", array($wpmjgu_settings_dialog, "ssh_server_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section", array('class' => 'displaynone'));
    register_setting("wpmjgu_settins_section", "wpmjgu_ssh_server",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "ssh_server_check") ));                

    add_settings_field("wpmjgu_ssh_port", "SSH Port", array($wpmjgu_settings_dialog, "ssh_port_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section", array('class' => 'displaynone'));
    register_setting("wpmjgu_settins_section", "wpmjgu_ssh_port",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "ssh_port_check") ));                    

    add_settings_field("wpmjgu_ssh_username", "SSH Username", array($wpmjgu_settings_dialog, "ssh_username_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section", array('class' => 'displaynone'));
    register_setting("wpmjgu_settins_section", "wpmjgu_ssh_username",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "ssh_username_check") ));                    

    add_settings_field("wpmjgu_ssh_password", "SSH Password", array($wpmjgu_settings_dialog, "ssh_password_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section", array('class' => 'displaynone'));
    register_setting("wpmjgu_settins_section", "wpmjgu_ssh_password",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "ssh_password_check") ));                        
    
    add_settings_field("wpmjgu_ssh_working_directory", "Working directory at SSH server", array($wpmjgu_settings_dialog, "ssh_working_directory_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section", array('class' => 'displaynone'));
    register_setting("wpmjgu_settins_section", "wpmjgu_ssh_working_directory",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "ssh_working_directory_check") ));                            
    
    add_settings_field("wpmjgu_mozjpeg_encoder_directory", "Directory with MozJpeg encoder's binaries", array($wpmjgu_settings_dialog, "mozjpeg_encoder_directory_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section");
    register_setting("wpmjgu_settins_section", "wpmjgu_mozjpeg_encoder_directory",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "mozjpeg_encoder_directory_check") ));                                

    add_settings_field("wpmjgu_guetzli_encoder_directory", "Directory with Guetzli encoder's binaries", array($wpmjgu_settings_dialog, "guetzli_encoder_directory_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section");
    register_setting("wpmjgu_settins_section", "wpmjgu_guetzli_encoder_directory",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "guetzli_encoder_directory_check") ));                                    

    add_settings_field("wpmjgu_webp_encoder_directory", "Directory with cwepb", array($wpmjgu_settings_dialog, "webp_encoder_directory_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section");
    register_setting("wpmjgu_settins_section", "wpmjgu_webp_encoder_directory",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "webp_encoder_directory_check") ));                                    
    
    
    add_settings_field("wpmjgu_settings_validation_error", "Settings validation error", array($wpmjgu_settings_dialog, "settings_validation_error_show") , $wpmjgu_func->url_file->PLUGIN_SLUG, "wpmjgu_settins_section", array('class' => 'displaynone'));
    register_setting("wpmjgu_settins_section", "wpmjgu_settings_validation_error",  array( 'sanitize_callback' => array($wpmjgu_settings_dialog, "settings_validation_error_check") ));                                    

    
}    
add_action("admin_init", "wpmjgu_display_options");




function wpmjgu_create_menu()
{
    wpmjgu_init_classes();
    global $wpmjgu_func;
    add_media_page( 'Opti MozJpeg Guetzli WebP', 'Opti MozJpeg Guetzli WebP', 'edit_files', $wpmjgu_func->url_file->PLUGIN_SLUG, 'wpmjgu_admin' );
}
add_action('admin_menu', 'wpmjgu_create_menu');

function wpmjgu_action_links( $links )
{
    global $wpmjgu_func;
    $links = array_merge( array(
	'<a href="' . esc_url( admin_url( "upload.php?page=" . $wpmjgu_func->url_file->PLUGIN_SLUG ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'
    ), $links );
    return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpmjgu_action_links' );




function ajaxhook_wpmjgu_batch_optimization_worker()
{
    if (! current_user_can('edit_files') ) { die("You don't have enough privileges to pefrom this task\n\n"); }
    
    set_time_limit(7200);
    $wpmjgu_batch_optimization_worker = new \wpmjgu\class_wpmjgu_batch_optimization_worker();
    $wpmjgu_batch_optimization_worker->the_html();
    die('');
}
add_action('wp_ajax_wpmjgu_batch_optimization_worker', 'ajaxhook_wpmjgu_batch_optimization_worker' );

function ajaxhook_wpmjgu_batch_revert_worker()
{
    if (! current_user_can('edit_files') ) { die("You don't have enough privileges to pefrom this task\n\n"); }
    
    set_time_limit(600);
    $wpmjgu_batch_revert_worker = new \wpmjgu\class_wpmjgu_batch_revert_worker();
    $wpmjgu_batch_revert_worker->the_html();
    die('');
}
add_action('wp_ajax_wpmjgu_batch_revert_worker', 'ajaxhook_wpmjgu_batch_revert_worker' );



function delete_wpmjgu_generated_files($ATTACHMENT_ID)
{
    wpmjgu_init_classes();
    global $wpmjgu_func;
    
    $ATTACHMENT= get_post($ATTACHMENT_ID);
    $ALL_WPMJGU_SETTINGS = $wpmjgu_func->get_all_wpmjgu_settings(); 
    $ORIGINAL_IMAGES_DIRECTORY = $ALL_WPMJGU_SETTINGS['wpmjgu_plugin_working_directory'] . "/" . $wpmjgu_func->url_file->ORIGINAL_IMAGES_SUBDIRECTORY;
    $current_path = $wpmjgu_func->url_file->wpuploads_url_to_path($ATTACHMENT->guid);

    $backup_path = $wpmjgu_func->url_file->has_backup($ATTACHMENT_ID, $current_path, $ORIGINAL_IMAGES_DIRECTORY);
    if ($backup_path)
    {
        $wpmjgu_func->url_file->wait_and_unlink($backup_path);
        $wpmjgu_func->url_file->wait_and_unlink($wpmjgu_func->url_file->mbPathWithoutExt($backup_path) . "_basename.txt");
        $wpmjgu_func->url_file->wait_and_unlink($wpmjgu_func->url_file->mbPathWithoutExt($backup_path) . "_guid.txt");
    }
    
    if (get_post_meta($ATTACHMENT_ID, 'wpmjgu_optimized', true) === 'true' ) {
        $wpmjgu_func->url_file->delete_attachment_thumbnails($current_path);
    }
    
    if (get_post_meta($ATTACHMENT_ID, 'wpmjgu_webp_duplicate', true) === 'true' ) {
        $wpmjgu_func->url_file->delete_webp_duplicates($current_path);    
    }

}
add_action('delete_attachment', 'delete_wpmjgu_generated_files');



function wpmjgu_admin_notices_action()
{
    settings_errors( );
}
add_action( 'admin_notices', 'wpmjgu_admin_notices_action' ); 