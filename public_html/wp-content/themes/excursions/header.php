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

	<?php
	$font_1_url = get_template_directory_uri() . '/assets/fonts/ubuntu-v13-cyrillic_latin-300.woff2';
	$font_2_url = get_template_directory_uri() . '/assets/fonts/ubuntu-v13-cyrillic_latin-regular.woff2';
	?>
	<link rel="preload" as="font" type="font/woff2" href="<?php echo esc_attr( $font_1_url ); ?>" crossorigin>
	<link rel="preload" as="font" type="font/woff2" href="<?php echo esc_attr( $font_2_url ); ?>" crossorigin>

	<style>@-ms-viewport{width:device-width}html{box-sizing:border-box;-ms-overflow-style:scrollbar}*,*::before,*::after{box-sizing:inherit}.container{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}@media (min-width:576px){.container{max-width:540px}}@media (min-width:768px){.container{max-width:720px}}@media (min-width:992px){.container{max-width:960px}}@media (min-width:1200px){.container{max-width:1140px}}.container-fluid{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}.row{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}.no-gutters{margin-right:0;margin-left:0}.no-gutters>.col,.no-gutters>[class*="col-"]{padding-right:0;padding-left:0}.col-1,.col-2,.col-3,.col-4,.col-5,.col-6,.col-7,.col-8,.col-9,.col-10,.col-11,.col-12,.col,.col-auto,.col-sm-1,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm,.col-sm-auto,.col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12,.col-md,.col-md-auto,.col-lg-1,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg,.col-lg-auto,.col-xl-1,.col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7,.col-xl-8,.col-xl-9,.col-xl-10,.col-xl-11,.col-xl-12,.col-xl,.col-xl-auto{position:relative;width:100%;min-height:1px;padding-right:15px;padding-left:15px}.col{-ms-flex-preferred-size:0;flex-basis:0%;-ms-flex-positive:1;flex-grow:1;max-width:100%}.col-auto{-ms-flex:0 0 auto;flex:0 0 auto;width:auto;max-width:none}.col-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-5{-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%}.col-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%}.col-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-11{-ms-flex:0 0 91.666667%;flex:0 0 91.666667%;max-width:91.666667%}.col-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}@media (min-width:576px){.col-sm{-ms-flex-preferred-size:0;flex-basis:0%;-ms-flex-positive:1;flex-grow:1;max-width:100%}.col-sm-auto{-ms-flex:0 0 auto;flex:0 0 auto;width:auto;max-width:none}.col-sm-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-sm-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-sm-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-sm-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-sm-5{-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%}.col-sm-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-sm-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-sm-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%}.col-sm-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-sm-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-sm-11{-ms-flex:0 0 91.666667%;flex:0 0 91.666667%;max-width:91.666667%}.col-sm-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}@media (min-width:768px){.col-md{-ms-flex-preferred-size:0;flex-basis:0%;-ms-flex-positive:1;flex-grow:1;max-width:100%}.col-md-auto{-ms-flex:0 0 auto;flex:0 0 auto;width:auto;max-width:none}.col-md-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-md-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-md-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-md-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-md-5{-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%}.col-md-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-md-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-md-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%}.col-md-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-md-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-md-11{-ms-flex:0 0 91.666667%;flex:0 0 91.666667%;max-width:91.666667%}.col-md-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}@media (min-width:992px){.col-lg{-ms-flex-preferred-size:0;flex-basis:0%;-ms-flex-positive:1;flex-grow:1;max-width:100%}.col-lg-auto{-ms-flex:0 0 auto;flex:0 0 auto;width:auto;max-width:none}.col-lg-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-lg-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-lg-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-lg-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-lg-5{-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%}.col-lg-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-lg-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-lg-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%}.col-lg-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-lg-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-lg-11{-ms-flex:0 0 91.666667%;flex:0 0 91.666667%;max-width:91.666667%}.col-lg-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}@media (min-width:1200px){.col-xl{-ms-flex-preferred-size:0;flex-basis:0%;-ms-flex-positive:1;flex-grow:1;max-width:100%}.col-xl-auto{-ms-flex:0 0 auto;flex:0 0 auto;width:auto;max-width:none}.col-xl-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-xl-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-xl-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-xl-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-xl-5{-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%}.col-xl-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-xl-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-xl-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%}.col-xl-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-xl-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-xl-11{-ms-flex:0 0 91.666667%;flex:0 0 91.666667%;max-width:91.666667%}.col-xl-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}</style>

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
					$site_name        = get_bloginfo();
					$is_front_page    = is_front_page();
					$nav_title        = null;
					$nav_title_ref    = null;
					$alt_contenttitle = null;
					$alt_headertitle  = null;
					$nav_menu_classes = '';
					$print_nav_title  = ! ( is_page( 'map' ) || is_page( 'citata' ) );

					if ( is_singular( 'guidebook' ) || is_tax( 'sections' ) ) {
						$guidebook_id    = get_page_by_path( 'guidebook' )->ID;
						$alt_headertitle = get_post_meta( $guidebook_id, 'header-title', true );
						$terms           = get_the_terms( $post->ID, 'sections' );
						$myterm          = $terms ? array_shift( $terms ) : null;
						if ( ( $myterm && 'sights' === $myterm->slug ) || is_tax( 'sections' ) ) {
							$nav_title        = get_the_title( $guidebook_id );
							$nav_title_ref    = get_permalink( $guidebook_id );
							$nav_menu_classes = 'menu-item-guidebook_hide';
							if ( is_tax( 'sections' ) ) {
								$alt_contenttitle = $myterm->name;
							}
						} else {
							$nav_title     = $myterm->name;
							$nav_title_ref = get_term_link( intval( $myterm->term_id ) );
						}
					} elseif ( ! is_404() ) {
						$alt_headertitle = get_post_meta( $post->ID, 'header-title', true );
					}

					$headertitle = $alt_headertitle ? $alt_headertitle : $site_name;

					if ( ! $nav_title ) {
						$nav_title = ( is_page() || is_home() ) ? single_post_title( null, false ) : '';
					}
					if ( ! $nav_title && is_archive() ) {
						$nav_title = get_the_archive_title();
					}
					if ( ! $nav_title && is_single() ) {
						// $ancestors = get_post_ancestors($post->ID);
						if ( is_singular( 'post' ) ) {
							$my_blog_id    = get_option( 'page_for_posts' );
							$nav_title     = get_the_title( $my_blog_id );
							$nav_title_ref = get_permalink( $my_blog_id );
						}
						if ( is_singular( 'events' ) ) {
							$nav_title     = 'События'; // get_the_archive_title();.
							$nav_title_ref = get_post_type_archive_link( 'events' );
						}
					}

					$h1_is           = false;
					$header_subtitle = null;
					if ( $is_front_page || ( $alt_headertitle && is_page() ) ) {
						$h1_is = true;
					}

					if ( $is_front_page ) {
						// 'мы не стоим на месте'.
						$header_subtitle = 'на удалёнке';
					}
					?>

					<?php if ( $h1_is ) : ?>
						<h1 class="header-title"><?php echo esc_html( $headertitle ); ?></h1>
					<?php else : ?>
						<h2 class="header-title"><?php echo esc_html( $headertitle ); ?></h2>
					<?php endif; ?>

					<?php if ( $header_subtitle ) : ?>
						<p class="header-subtitle"><?php echo esc_html( $header_subtitle ); ?></p>
					<?php endif; ?>

					</div>

					<?php
					if ( function_exists( 'the_custom_logo' ) ) {
						the_custom_logo();
					}
					?>

					<meta itemprop="name" content="<?php echo esc_attr( $site_name ); ?>">

				</div> <!-- header-container itemtype="http://schema.org/Organization" --> 
			</div>
		</div>

		<div class="container nav-container">
			<nav id="nav-block" class="fixable">
				<div class="row">
					<div class="col">
						<div>
							<div id="nav-adaptive" class="flex-container">
								<button id="menu" class="hidden">
									<span></span>
									<span></span>
									<span></span>
									<span></span>
								</button>
								<?php if ( $nav_title && $print_nav_title ) : ?>
									<div class="nav-title">
										<?php if ( $nav_title_ref ) : ?>
											<a href="<?php echo esc_attr( $nav_title_ref ); ?>">
										<?php endif; ?>

										<?php echo esc_html( $nav_title ); ?>

										<?php if ( $nav_title_ref ) : ?>
											</a>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<div>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'header_menu',
									'container'      => false,
									'menu_class'     => 'nav-menu ' . $nav_menu_classes,
								)
							);
							?>
						</div>
					</div>
				</div>
			</nav>
		</div>
	</header><!-- #masthead -->

	<a class="b-up" href="#" id="up" title="Наверх">
		<svg class="b-up__icon" viewBox="0 0 22 22" id="ui-arrow-block_up" width="100%" height="100%"><g fill-rule="evenodd">
		<path d="M18.006 0A3.996 3.996 0 0 1 22 3.994v14.012A3.996 3.996 0 0 1 18.006 22H3.994A3.996 3.996 0 0 1 0 18.006V3.994A3.996 3.996 0 0 1 3.994 0h14.012z" fill="#D5D5D5" fill-opacity="0.5"></path>
		<path d="M18.006 1H3.994A2.996 2.996 0 0 0 1 3.994v14.012C1 19.656 2.34 21 3.994 21h14.012C19.656 21 21 19.66 21 18.006V3.994C21 2.344 19.66 1 18.006 1z" fill="#FFF" fill-opacity="0.5"></path>
		<path d="M11.136 8.268L10.87 8l-4.597 4.596 1.132 1.132 3.732-3.733 3.733 3.733L16 12.596 11.404 8l-.268.268z"></path>
		</g></svg>
	</a>

	<?php if ( function_exists( 'bcn_display' ) && ! $is_front_page ) : ?>
		<div class="breadcrumbs container" typeof="BreadcrumbList" vocab="https://schema.org/">
			<?php bcn_display(); ?>
		</div>
	<?php endif; ?>

	<div id="content" class="site-content">
		<div class="container main-container">								
		<?php
		if ( ! $h1_is && $nav_title && ! is_single() ) :
			if ( ! $alt_contenttitle ) {
				$alt_contenttitle = is_post_type_archive( 'events' ) ? 'Наши мероприятия' : get_post_meta( get_queried_object()->ID, 'content-title', true );
			}
			if ( $alt_contenttitle ) {
				$nav_title = $alt_contenttitle;
			}
			?>

			<h1 class="content-title"><?php echo esc_html( $nav_title ); ?></h1>

		<?php endif; ?>
