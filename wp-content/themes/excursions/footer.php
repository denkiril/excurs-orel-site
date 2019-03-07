<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package excursions
 */

?>

	<!-- <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script> -->
	<!-- <script src="//yastatic.net/share2/share.js"></script> -->
	<?php if( !(is_404() || is_search()) ): ?>
	<div class="soc-buttons">
		<span>Поделиться: </span>
		<noscript>у вас выключены скрипты, без них вы можете поделиться этой страницей вручную.</noscript>
		<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter,viber,whatsapp,skype,telegram"></div>
	</div>
	<?php endif; ?>

	</div> <!-- .main-container -->
	</div> <!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="container footer-container">
			<div class="row">
				<div class="col-12 col-sm-6 col-lg-6 footer-links">
					<?php 
					get_search_form();

					wp_nav_menu( array(
						'theme_location'  => 'footer_menu',
						// 'menu'            => 'menu-1',
						'container'       => false,
						'menu_class'      => 'footer-menu'
					) ); 
					?>
				</div>
				<div class="col-12 col-sm-6 col-lg-6">
					<div class="soc-container">
						<a href="https://vk.com/excurs_orel" target="_blank" title="Мы ВКонтакте">
							<svg class="svg-logo vk-logo" viewBox="0 0 112.196 112.196">
								<path d="M 56.09375 0 C 25.11168 0 -8.6798002e-017 25.11168 0 56.09375 C 0 87.075819 25.11168 112.1875 56.09375 112.1875 C 87.075819 112.1875 112.1875 87.075819 112.1875 56.09375 C 112.1875 25.11168 87.075819 -6.8076864e-018 56.09375 0 z M 50.53125 38.875 C 51.430187 38.861297 52.387 38.86475 53.375 38.875 C 56.454 38.906 57.32725 39.084 58.53125 39.375 C 62.15925 40.251 60.9375 43.634 60.9375 51.75 C 60.9375 54.348 60.46875 58.00675 62.34375 59.21875 C 63.14975 59.73875 65.09925 59.3125 70.03125 50.9375 C 72.36925 46.9685 74.125 42.28125 74.125 42.28125 C 74.125 42.28125 74.53 41.45075 75.125 41.09375 C 75.734 40.72975 76.53125 40.84375 76.53125 40.84375 L 87.625 40.78125 C 87.625 40.78125 90.961 40.373 91.5 41.875 C 92.065 43.452 90.24375 47.1565 85.71875 53.1875 C 78.28875 63.0905 77.457 62.15 83.625 67.875 C 89.518 73.345 90.7375 76.01275 90.9375 76.34375 C 93.3775 80.38975 88.25 80.6875 88.25 80.6875 L 78.40625 80.84375 C 78.40625 80.84375 76.282 81.25475 73.5 79.34375 C 69.821 76.81875 66.35925 70.268 63.65625 71.125 C 60.91425 71.995 61 77.875 61 77.875 C 61 77.875 61.03125 79.1405 60.40625 79.8125 C 59.72725 80.5445 58.375 80.6875 58.375 80.6875 L 53.96875 80.6875 C 53.96875 80.6875 44.27375 81.282 35.71875 72.375 C 26.38875 62.66 18.125 43.375 18.125 43.375 C 18.125 43.375 17.6715 42.111 18.1875 41.5 C 18.7675 40.813 20.34375 40.78125 20.34375 40.78125 L 30.875 40.71875 C 30.875 40.71875 31.8525 40.88525 32.5625 41.40625 C 33.1475 41.83625 33.46875 42.625 33.46875 42.625 C 33.46875 42.625 35.1845 46.9205 37.4375 50.8125 C 41.8345 58.4105 43.882 60.09525 45.375 59.28125 C 47.55 58.09425 46.90625 48.53125 46.90625 48.53125 C 46.90625 48.53125 46.9475 45.07525 45.8125 43.53125 C 44.9335 42.33425 43.26325 41.973 42.53125 41.875 C 41.93925 41.796 42.89625 40.4295 44.15625 39.8125 C 45.57675 39.1165 47.834438 38.916109 50.53125 38.875 z " />
							</svg>						
						</a>
						<a href="https://www.facebook.com/groups/excursorel" target="_blank" title="Мы в фейсбуке">
							<svg class="svg-logo fb-logo" viewBox="0 0 112.196 112.196">
								<path d="M 56.09375 0 C 25.111681 6.2265008e-015 -8.6798001e-017 25.111681 0 56.09375 C 0 87.075819 25.111681 112.1875 56.09375 112.1875 C 87.075819 112.1875 112.1875 87.075819 112.1875 56.09375 C 112.1875 25.111681 87.075819 -1.2462122e-014 56.09375 0 z M 60.3125 21.75 L 71.5625 21.8125 L 71.5625 34.3125 L 63.40625 34.3125 C 62.06925 34.3125 60.1875 34.99875 60.1875 37.84375 L 60.1875 45.40625 L 71.53125 45.40625 L 70.1875 58.28125 L 60.1875 58.28125 L 60.1875 94.96875 L 45.03125 94.96875 L 45.03125 58.28125 L 37.8125 58.28125 L 37.8125 45.40625 L 45.03125 45.40625 L 45.03125 37.0625 C 45.03125 31.0985 47.8445 21.75 60.3125 21.75 z " />
							</svg>
						</a>
						<a href="https://www.instagram.com/excurs_orel/" target="_blank" title="Мы в инстаграме">
							<svg class="svg-logo ig-logo" viewBox="0 0 89.758 89.758">
								<path d="M 58.255,23.88 H 31.503 c -4.27,0 -7.744,3.474 -7.744,7.744 v 26.752 c 0,4.27 3.474,7.745 7.744,7.745 h 26.752 c 4.27,0 7.745,-3.474 7.745,-7.745 V 31.624 C 66,27.354 62.526,23.88 58.255,23.88 z M 44.879,58.906 c -7.667,0 -13.905,-6.238 -13.905,-13.906 0,-7.667 6.238,-13.905 13.905,-13.905 7.668,0 13.906,6.238 13.906,13.905 0,7.667 -6.239,13.906 -13.906,13.906 z M 59.232,33.97 c -1.815,0 -3.291,-1.476 -3.291,-3.29 0,-1.814 1.476,-3.29 3.291,-3.29 1.814,0 3.29,1.476 3.29,3.29 0,1.814 -1.476,3.29 -3.29,3.29 z" />
								<path d="m 44.879,36.971 c -4.426,0 -8.03,3.602 -8.03,8.028 0,4.428 3.604,8.031 8.03,8.031 4.428,0 8.029,-3.603 8.029,-8.031 0,-4.425 -3.602,-8.028 -8.029,-8.028 z" />
								<path d="M 44.879,0 C 20.094,0 0,20.094 0,44.879 0,69.664 20.094,89.758 44.879,89.758 69.664,89.758 89.758,69.664 89.758,44.879 89.758,20.094 69.664,0 44.879,0 z m 26.996,58.376 c 0,7.511 -6.109,13.62 -13.62,13.62 H 31.503 c -7.51,0 -13.62,-6.109 -13.62,-13.62 V 31.624 c 0,-7.51 6.11,-13.62 13.62,-13.62 h 26.752 c 7.511,0 13.62,6.11 13.62,13.62 v 26.752 z" />
							</svg>
						</a>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col copyr-container footer-links">
					<p>© 2015-2019 <a href="<?php echo home_url(); ?>">«Экскурсии по Орлу»</a></p>
					<p>© Разработка и поддержка сайта: <a href="https://github.com/denkiril" target="_blank" rel="noopener noreferrer">Денис Кирилюк</a></p>
					<p class="sub_copyr">При использовании наших материалов, не забывайте, пожалуйста, ссылаться на наш сайт.</p>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

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

</body>
</html>
