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

	<?php $is_front_page = is_front_page();
	if($is_front_page): ?>
		<meta name="description" content="<?php bloginfo('description'); ?>" />
	<?php elseif( is_single() || is_page() ): if( have_posts() ): while ( have_posts() ): the_post(); ?>
		<meta name="description" content="<?php the_excerpt(); ?>" />
	<?php endwhile; endif; else: ?>
		<meta name="description" content="<?php bloginfo('description'); ?>" />
	<?php endif; ?>

	<link rel="shortcut icon" href="/favicon.ico" />
	<!-- <title><?=get_the_title()?></title> -->
	<!-- <style>.svg-logo{max-width:60px; max-height:60px;}</style> -->

	<?php wp_head(); ?>

	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
	(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
	m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

	ym(52494430, "init", {
			clickmap:true,
			trackLinks:true,
			accurateTrackBounce:true,
			webvisor:true
	});
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/52494430" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-46857559-2"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-46857559-2');
	</script>

</head>

<body> 
<div id="page">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'excursions' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container">
			<div class="row">
                <div class="col header-container flex-container">
					<div>
					<?php 
						$alt_headertitle = get_post_meta( $post->ID, 'header-title', true ); 
						$headertitle = $alt_headertitle ? $alt_headertitle : get_bloginfo();
						if ( $is_front_page || $alt_headertitle ) :
							$headertitle_html = '<h1 class="header-title">'.$headertitle.'</h1>';
							$h1_is = true;
						else :
							$headertitle_html = '<h2 class="header-title">'.$headertitle.'</h2>';
							$h1_is = false;
						endif;
						echo $headertitle_html;

						if( $is_front_page) echo '<p class="header-subtitle">мы не стоим на месте</p>';

						$nav_title = (is_page() || is_home()) ? single_post_title(null, false) : '';
						if( !$nav_title && is_archive() ) $nav_title = get_the_archive_title();
						if( !$nav_title && is_single() ){
							// $ancestors = get_post_ancestors($post->ID);
							if( is_singular('post') ){
								$blog_id = get_option('page_for_posts');
								$nav_title = get_the_title( $blog_id );
								$nav_title_ref = get_permalink( $blog_id );
							} 
							if( is_singular('events') ){
								$nav_title = 'События';
								$nav_title_ref = get_post_type_archive_link('events');
							} 
						}
					?>
					</div>
					
					<?php if ( function_exists('the_custom_logo') ) the_custom_logo(); ?>
					<!-- <a href="<?=home_url()?>"> -->
						<!-- <img src="<?=get_template_directory_uri()?>/assets/img/Logo_200.png" alt="Экскурсии по Орлу" /> -->
					<!-- </a> -->
                </div>
            </div>
		</div>

		<div class="container nav-container">
			<nav id="nav-block">
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
			if( is_post_type_archive('events') ) $alt_contenttitle = 'Наши мероприятия';
			else $alt_contenttitle = get_post_meta( get_queried_object()->ID, 'content-title', true );
			if( $alt_contenttitle ) $nav_title = $alt_contenttitle;
			?>
			<h1 class="content-title"><?=$nav_title?></h1>
		<?php endif; ?>
