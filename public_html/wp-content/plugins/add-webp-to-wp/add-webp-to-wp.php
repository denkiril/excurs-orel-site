<?php
/*
 * Plugin Name: Add WebP to WP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Hook for adding admin menus
add_action('admin_menu', 'awpwp_add_pages');

// action function for above hook
function awpwp_add_pages() {
    // Add a new submenu under Manage:
    add_management_page('Add WebP to WP', 'Add WebP to WP', 8, 'add-webp-to-wp', 'awpwp_manage_page');
}

// mt_manage_page() displays the page content for the Test Manage submenu
function awpwp_manage_page() {
    $args = array( 'post_type' => 'attachment', 'posts_per_page' => -1, 'post_status' => null, 'post_parent' => null );
    $attachments = get_posts( $args );
    $att_count = count($attachments);
    $args['post_mime_type'] = 'image/jpeg';
    $jpeg_atts = get_posts( $args );
    // $jpeg_count = count($jpeg_atts);
    $args['post_mime_type'] = 'image/webp';
    $webp_atts = get_posts( $args );
    // $webp_count = count($webp_atts);
    
    $upload_dir = wp_get_upload_dir();
    $directory = $upload_dir['basedir'];
    $files = glob($directory.'/*');
    $file_count = $files ? count($files) : 0;
    $files = glob($directory . '/*.{jpg,png,gif}', GLOB_BRACE);
    $img_count = $files ? count($files) : 0;
    $files = glob($directory . '/*.webp');
    $webp_count = $files ? count($files) : 0;

    ?>

    <div class="wrap">
        <h2>Welcome to "Add WebP to WP" Plugin</h2>
        <p>Attachments count: <?=$att_count?></p>
        <p>jpeg count: <?=count($jpeg_atts)?></p>
        <p>webp count: <?=count($webp_atts)?></p>
        <p>Upload dir: <?=$directory?></p>
        <p>Files count: <?=$file_count?></p>
        <p>jpg,png,gif files count: <?=$img_count?></p>
        <p>webp files count: <?=$webp_count?></p>

    </div>

<?php } ?>
