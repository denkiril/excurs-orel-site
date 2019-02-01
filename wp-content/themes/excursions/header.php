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
	<meta name="description" content="Если вы искали Экскурсии по Орлу, то вы нашли! ;)" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu:300,400&amp;subset=cyrillic" />
    <!-- <link rel="stylesheet" href="include/bootstrap-grid.min.css" />
    <link rel="stylesheet" type="text/css" href="include/slick.css"/>
    <link rel="stylesheet" type="text/css" href="include/slick-theme.css"/>
    <link rel="stylesheet" href="css/main.css" /> -->
	<title>Экскурсии по Орлу</title>

	<?php wp_head(); ?>
</head>

<body> 
<div id="page">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'excursions' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container">
			<div class="row">
                <div class="col header-container flex-container">
					<?php 
						$headertitle = get_bloginfo();
						$postmeta = get_post_meta( $post->ID, 'header-h1', true ); 
						if($postmeta) $headertitle = $postmeta;
						if ( is_page() ) :
							$headertitle_html = '<h1 class="header-title">'.$headertitle.'</h1>';
							$navtitle = get_the_title();
						else :
							$headertitle_html = '<h2 class="header-title">'.$headertitle.'</h2>';
							$navtitle = $post->ID;
						endif;
						echo $headertitle_html;
					?>
					<!-- <h1 class="logo-wrapper"><a href="/">Экскурсии по Орлу</a></h1>  -->
					<a href="<?php echo home_url(); ?>">
						<img src="<?php echo get_template_directory_uri() ?> . /assets/img/Logo_200.png" alt="Экскурсии по Орлу" />
					</a>
                </div>
            </div>
		</div>

		<div class="container nav-container">
			<nav id="nav-block" role="navigation">
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
								<div class="nav-title"><?php echo $navtitle; ?></div>
							</div>
						</div>
						<!--<div class="nav-container">
							<ul class="nav-menu">
								 <li class="nav-item act-item"><a href="/"><span>ЭКСКУРСИИ</span></a></li> 
								<li class="nav-item"><a href="<?php echo home_url(); ?>"><span>ЭКСКУРСИИ</span></a></li>
								<li class="nav-item"><a href="<?php echo home_url('/kvesty'); ?>"><span>КВЕСТЫ</span></a></li>
								<li class="nav-item"><a href="#"><span>СУВЕНИРЫ</span></a></li>
								<li class="nav-item"><a href="#"><span>ПУТЕВОДИТЕЛЬ</span></a></li>
								<li class="nav-item"><a href="#"><span>ЗАКАЗАТЬ<br>ЭКСКУРСИЮ</span></a></li>
							</ul>
						</div>-->
						<!-- <div class="nav-container"> -->
						<div>
							<?php wp_nav_menu( array(
								'theme_location'  => 'Primary',
								'menu'            => 'menu-1',
								'container'       => false,
								'menu_class'      => 'nav-menu'
							 ) ); ?>
						</div>
					</div>
				</div>
			</nav>
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
