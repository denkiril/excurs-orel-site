<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package excursions
 */

?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<meta http-equiv="X-UA-Compatible" content="ie=edge" />

	<link rel="shortcut icon" href="/favicon.ico">

	<?php $theme_path = get_template_directory_uri(); ?>
	<link rel="preload" as="font" type="font/woff2" href="<?=$theme_path?>/assets/fonts/ubuntu-v13-cyrillic_latin-300.woff2" crossorigin>
	<link rel="preload" as="font" type="font/woff2" href="<?=$theme_path?>/assets/fonts/ubuntu-v13-cyrillic_latin-regular.woff2" crossorigin>
	
	<?php wp_head(); ?>
</head>

<body> 
<div id="page">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'excursions' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container">
			<div class="row">
                <div class="col header-container flex-container" itemscope itemtype="http://schema.org/Organization">
					<div>
					<?php 
						$site_name = get_bloginfo();
						$is_front_page = is_front_page();
						
						if( is_singular('guidebook') || is_tax('sections') ){
							$guidebook_id 		= 789;
							if(home_url() == 'http://excurs-orel'){
								$guidebook_id 	= 697;
							}
							$alt_headertitle 	= get_post_meta( $guidebook_id, 'header-title', true ); 
							$term_0 			= get_terms( array('taxonomy' => 'sections') )[0]; // 'sights'
							$term 				= get_the_terms($post->ID, 'sections')[0];
							if(!$term_0 || !$term || $term->term_id == $term_0->term_id || is_tax('sections')){
								// $nav_title 		= $term->name;
								// $nav_title_ref 	= get_term_link( (int)$term->term_id );
								$nav_title 			= get_the_title($guidebook_id); // Путеводитель
								$nav_title_ref 		= get_permalink($guidebook_id);
								if(is_tax('sections')){
									$alt_contenttitle = $term->name;
								}
							}
							// elseif(is_tax('sections') && !is_tax('sections', 'sights')){}
							else{
								// $term 			= get_queried_object();
								$nav_title 		= $term->name;
								$nav_title_ref 	= get_term_link( (int)$term->term_id );
							}
						}
						else{
							$alt_headertitle = get_post_meta( $post->ID, 'header-title', true ); 
						}

						$headertitle = $alt_headertitle ? $alt_headertitle : $site_name;

						if( $is_front_page || ($alt_headertitle && is_page()) ){
							$headertitle_html = '<h1 class="header-title">'.$headertitle.'</h1>';
							$h1_is = true;
						}
						else{
							$headertitle_html = '<h2 class="header-title">'.$headertitle.'</h2>';
							$h1_is = false;
						}
						
						if( $is_front_page){
							$headertitle_html .= '<p class="header-subtitle">мы не стоим на месте</p>';
						} 

						if( !$nav_title ){
							$nav_title = (is_page() || is_home()) ? single_post_title(null, false) : '';
						}
						if( !$nav_title && is_archive() ){
							$nav_title = get_the_archive_title();
						}
						if( !$nav_title && is_single() ){
							// $ancestors = get_post_ancestors($post->ID);
							if( is_singular('post') ){
								$blog_id 		= get_option('page_for_posts');
								$nav_title 		= get_the_title( $blog_id );
								$nav_title_ref 	= get_permalink( $blog_id );
							} 
							if( is_singular('events') ){
								$nav_title 		= 'События'; // get_the_archive_title();
								$nav_title_ref 	= get_post_type_archive_link('events');
							} 
							// if( is_singular('guidebook') ){} 
						}

						echo $headertitle_html;
					?>
					</div>
						
					<?php if ( function_exists('the_custom_logo') ) the_custom_logo(); ?>

					<meta itemprop="name" content="<?=$site_name?>">

				</div> <!-- header-container itemtype="http://schema.org/Organization" --> 
            </div>
		</div>

		<div class="container nav-container">
			<nav id="nav-block" class="fixable">
				<div class="row">
					<div class="col">
						<!-- <div class="nav-container"> -->
						<div>
							<div id="nav-adaptive" class="flex-container">
								<button id="menu">
									<span></span>
									<span></span>
									<span></span>
									<span></span>
								</button>
								<div class="nav-title">
									<?php 
									if( $nav_title_ref ) echo '<a href="'.$nav_title_ref.'" title="Ссылка на '.$nav_title.'">';
									echo $nav_title;
									if( $nav_title_ref ) echo '</a>'; 
									?>
								</div>
							</div>
						</div>
						<div>
							<?php wp_nav_menu( array(
								'theme_location'  => 'header_menu',
								// 'menu'            => 'menu-1',
								'container'       => false,
								'menu_class'      => 'nav-menu'
							 ) ); ?>
						</div>
					</div>
				</div>
			</nav>
		</div>
	</header><!-- #masthead -->

	<?php if(function_exists('bcn_display') && !$is_front_page ): ?>
		<div class="breadcrumbs container" typeof="BreadcrumbList" vocab="https://schema.org/">
			<?php bcn_display(); ?>
		</div>
	<?php endif; ?>

	<div id="content" class="site-content">
		<div class="container main-container">								
		<?php if( !$h1_is && $nav_title && !is_single() ): 
			if(!$alt_contenttitle){
				if( is_post_type_archive('events') ) $alt_contenttitle = 'Наши мероприятия';
				else $alt_contenttitle = get_post_meta( get_queried_object()->ID, 'content-title', true );
			}
			if( $alt_contenttitle ) $nav_title = $alt_contenttitle;
			?>
			<h1 class="content-title"><?=$nav_title?></h1>
		<?php endif; ?>
