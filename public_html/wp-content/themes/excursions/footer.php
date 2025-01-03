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

	<?php
	// $mypost_type = get_post_type();
	// $no_ads_page = is_page( 'kvesty' ) || is_page( 'gid-v-orle' ) || is_page( 'map' );
	// if ( 'events' !== $mypost_type && 'post' !== $mypost_type && ! $no_ads_page ) :
		?>
		<!-- Yandex.RTB R-A-414612-1 -->
		<!-- <div id="yandex_rtb_R-A-414612-1" style="padding: 20px 0 40px 0; min-height: 150px;"></div> -->

	<?php /* endif; */ ?>

	<?php if ( ! ( is_404() || is_search() || is_page( 'search' ) ) ) : ?>

		<div id="soc-buttons">
			<span>Поделиться: </span>
			<div class="ya-share2" data-services="vkontakte,odnoklassniki,moimir,twitter,viber,whatsapp,skype,telegram"></div>
		</div>

		<?php
		do_action( 'add_share_scripts' );
	endif;
	?>

	</div> <!-- .main-container -->
	</div> <!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="container footer-container">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-xl-4 footer-links">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer_menu',
							'container'      => false,
							'menu_class'     => 'footer-menu',
						)
					);
					?>

					<!-- <a href="https://daria-orel.mave.digital" target="_blank" rel="noopener noreferrer" title="Подкаст «Истории с Дарьей Фурманской»">
						Подкаст про Орёл
					</a> -->

					<div class="search-form">
						<div class="ya-site-form ya-site-form_inited_no" data-bem="{&quot;action&quot;:&quot;https://excurs-orel.ru/search/&quot;,&quot;arrow&quot;:false,&quot;bg&quot;:&quot;transparent&quot;,&quot;fontsize&quot;:14,&quot;fg&quot;:&quot;#000000&quot;,&quot;language&quot;:&quot;ru&quot;,&quot;logo&quot;:&quot;rb&quot;,&quot;publicname&quot;:&quot;Поиск по excurs-orel.ru&quot;,&quot;suggest&quot;:true,&quot;target&quot;:&quot;_blank&quot;,&quot;tld&quot;:&quot;ru&quot;,&quot;type&quot;:2,&quot;usebigdictionary&quot;:true,&quot;searchid&quot;:2340921,&quot;input_fg&quot;:&quot;#000000&quot;,&quot;input_bg&quot;:&quot;#ffffff&quot;,&quot;input_fontStyle&quot;:&quot;normal&quot;,&quot;input_fontWeight&quot;:&quot;normal&quot;,&quot;input_placeholder&quot;:&quot;Поиск по сайту&quot;,&quot;input_placeholderColor&quot;:&quot;#000000&quot;,&quot;input_borderColor&quot;:&quot;#7f9db9&quot;}">
							<form action="https://yandex.ru/search/site/" method="get" target="_blank" accept-charset="utf-8">
								<input type="hidden" name="searchid" value="2340921"/>
								<input type="hidden" name="l10n" value="ru"/>
								<input type="hidden" name="reqenc" value=""/>
								<input type="search" name="text" value=""/>
								<input type="submit" value="Найти"/>
							</form>
						</div>
						<style type="text/css">.ya-page_js_yes .ya-site-form_inited_no { display: none; }</style>
						<script type="text/javascript">(function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0],e=d.documentElement;if((' '+e.className+' ').indexOf(' ya-page_js_yes ')===-1){e.className+=' ya-page_js_yes';}s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Form.init()})})(window,document,'yandex_site_callbacks');</script>
					</div>

				</div>
				<div class="col-sm-12 col-md-6 col-xl-8">
					<div class="soc-container">
						<a href="https://vk.com/excurs_orel" target="_blank" rel="noopener noreferrer" title="Мы ВКонтакте">
              <svg class="svg-logo vk-back-color" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <circle r="50" cx="50" cy="50" />
                <path class="vk-front-color" fill="#3f3f3f" fill-opacity="1" d="M53.7085 72.042C30.9168 72.042 17.9169 56.417 17.3752 30.417H28.7919C29.1669 49.5003 37.5834 57.5836 44.25 59.2503V30.417H55.0004V46.8752C61.5837 46.1669 68.4995 38.667 70.8329 30.417H81.5832C79.7915 40.5837 72.2915 48.0836 66.9582 51.1669C72.2915 53.6669 80.8336 60.2086 84.0836 72.042H72.2499C69.7082 64.1253 63.3754 58.0003 55.0004 57.1669V72.042H53.7085Z" />
              </svg>
						</a>
						<a href="https://vkvideo.ru/@excurs_orel" target="_blank" rel="noopener noreferrer" title="ВК Видео">
              <svg class="svg-logo" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                <circle r="18" cx="18" cy="18" />
                <g transform="translate(6, 6)" fill="#3f3f3f">
                  <path class="vk-back-color opaque-on-hover" d="M 0,8.77 C 0,1.55 1.55,0 8.77,0 h 6.46 C 22.45,0 24,1.55 24,8.77 v 6.46 C 24,22.45 22.45,24 15.23,24 H 8.77 C 1.55,24 0,22.45 0,15.23 Z" />
                  <path class="vk-video-color" fill-opacity="1" d="m 12.9,24 h 2.28 c 7.1,0 8.74,-1.48 8.82,-8.28 V 8.24 C 23.92,1.47 22.26,0 15.18,0 H 12.9 C 5.64,0 4.08,1.55 4.08,8.77 v 6.46 c 0,7.22 1.56,8.77 8.82,8.77 z" />
                  <path class="opaque-on-hover" fill="#fff" d="m 16.93,9.92 c 1.22,0.7 1.82,1.05 2.03,1.51 0.18,0.4 0.18,0.86 0,1.25 -0.2,0.46 -0.81,0.81 -2.03,1.51 l -3.32,1.92 c -1.21,0.7 -1.82,1.05 -2.32,1 a 1.54,1.54 0 0 1 -1.08,-0.63 c -0.3,-0.4 -0.3,-1.1 -0.3,-2.5 v -3.84 c 0,-1.4 0,-2.1 0.3,-2.5 A 1.54,1.54 0 0 1 11.29,7 c 0.5,-0.06 1.1,0.3 2.32,1 l 3.32,1.91 z" />
                </g>
              </svg>					
						</a>
						<a href="https://www.facebook.com/groups/excursorel" target="_blank" rel="noopener noreferrer" title="Мы в фейсбуке" class="hide">
							<svg class="svg-logo fb-logo" viewBox="0 0 112.196 112.196" xmlns="http://www.w3.org/2000/svg">
								<path d="M 56.09375 0 C 25.111681 6.2265008e-015 -8.6798001e-017 25.111681 0 56.09375 C 0 87.075819 25.111681 112.1875 56.09375 112.1875 C 87.075819 112.1875 112.1875 87.075819 112.1875 56.09375 C 112.1875 25.111681 87.075819 -1.2462122e-014 56.09375 0 z M 60.3125 21.75 L 71.5625 21.8125 L 71.5625 34.3125 L 63.40625 34.3125 C 62.06925 34.3125 60.1875 34.99875 60.1875 37.84375 L 60.1875 45.40625 L 71.53125 45.40625 L 70.1875 58.28125 L 60.1875 58.28125 L 60.1875 94.96875 L 45.03125 94.96875 L 45.03125 58.28125 L 37.8125 58.28125 L 37.8125 45.40625 L 45.03125 45.40625 L 45.03125 37.0625 C 45.03125 31.0985 47.8445 21.75 60.3125 21.75 z " />
							</svg>
						</a>
						<a href="https://www.instagram.com/excurs_orel/" target="_blank" rel="noopener noreferrer" title="Мы в инстаграме" class="hide">
							<svg class="svg-logo ig-logo" viewBox="0 0 89.758 89.758" xmlns="http://www.w3.org/2000/svg">
								<path d="M 58.255,23.88 H 31.503 c -4.27,0 -7.744,3.474 -7.744,7.744 v 26.752 c 0,4.27 3.474,7.745 7.744,7.745 h 26.752 c 4.27,0 7.745,-3.474 7.745,-7.745 V 31.624 C 66,27.354 62.526,23.88 58.255,23.88 z M 44.879,58.906 c -7.667,0 -13.905,-6.238 -13.905,-13.906 0,-7.667 6.238,-13.905 13.905,-13.905 7.668,0 13.906,6.238 13.906,13.905 0,7.667 -6.239,13.906 -13.906,13.906 z M 59.232,33.97 c -1.815,0 -3.291,-1.476 -3.291,-3.29 0,-1.814 1.476,-3.29 3.291,-3.29 1.814,0 3.29,1.476 3.29,3.29 0,1.814 -1.476,3.29 -3.29,3.29 z" />
								<path d="m 44.879,36.971 c -4.426,0 -8.03,3.602 -8.03,8.028 0,4.428 3.604,8.031 8.03,8.031 4.428,0 8.029,-3.603 8.029,-8.031 0,-4.425 -3.602,-8.028 -8.029,-8.028 z" />
								<path d="M 44.879,0 C 20.094,0 0,20.094 0,44.879 0,69.664 20.094,89.758 44.879,89.758 69.664,89.758 89.758,69.664 89.758,44.879 89.758,20.094 69.664,0 44.879,0 z m 26.996,58.376 c 0,7.511 -6.109,13.62 -13.62,13.62 H 31.503 c -7.51,0 -13.62,-6.109 -13.62,-13.62 V 31.624 c 0,-7.51 6.11,-13.62 13.62,-13.62 h 26.752 c 7.511,0 13.62,6.11 13.62,13.62 v 26.752 z" />
							</svg>
						</a>
						<a href="https://www.youtube.com/channel/UCmPLUt16GHq_o4vC0AMY4rA" target="_blank" rel="noopener noreferrer" title="Ютуб-канал Дарьи Фурманской" class="yt-logo">
							<svg class="svg-logo" viewBox="0 0 90 90" xmlns="http://www.w3.org/2000/svg">
								<path d="m 38.954235,36.296881 c -0.04114,0.01988 -0.07078,0.06698 -0.07216,0.150346 l 0.192443,16.923022 c 0.0031,0.08629 0.07085,0.147402 0.156361,0.106245 L 54.25552,44.862632 c 0.04435,-0.02648 0.0666,-0.109873 0.0021,-0.16037 L 39.102577,36.316927 c -0.05359,-0.03119 -0.107206,-0.03993 -0.148342,-0.02005 z" />
								<path d="M 44.878906,1.884e-4 C 20.09391,1.884e-4 0,20.094094 0,44.879094 0,69.664094 20.09391,89.758 44.878906,89.758 c 24.785,0 44.878906,-20.093906 44.878906,-44.878906 0,-24.785 -20.093906,-44.8789056 -44.878906,-44.8789056 z m 0.25586,23.5449216 c 3.416226,0.0044 6.833774,0.07254 10.25,0.191406 11.98527,0.41703 14.816996,1.2725 16.435546,2.927734 1.618553,1.655237 2.873675,3.847842 3.144532,9.732422 0.224205,4.870988 0.288514,10.218723 0,16.619141 -0.265272,5.884834 -1.52598,8.075235 -3.144532,9.730469 -1.618552,1.655236 -4.450276,2.512657 -16.435546,2.929687 -6.832452,0.237737 -13.665594,0.273258 -20.498047,0 -11.982916,-0.479246 -14.816996,-1.274451 -16.435547,-2.929687 -1.618553,-1.655234 -2.824368,-3.848336 -3.144531,-9.730469 -0.331423,-6.089024 -0.437125,-11.803768 0,-16.619141 0.53258,-5.86689 1.525978,-8.077186 3.144531,-9.732422 1.618551,-1.655234 4.452631,-2.448488 16.435547,-2.927734 3.416226,-0.136626 6.83182,-0.195806 10.248047,-0.191406 z" />
							</svg>
              <svg class="yt-logo-hovered" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="m 45.135047,23.419924 c -3.436407,-0.0044 -6.872176,0.0551 -10.308582,0.192537 -12.053698,0.482077 -14.904519,1.280017 -16.532632,2.945028 -1.628114,1.665013 -2.62738,3.888366 -3.163106,9.789911 -0.439707,4.843818 -0.33338,10.592319 0,16.717311 0.322055,5.916878 1.534992,8.122935 3.163106,9.787947 1.628113,1.665013 4.478934,2.464915 16.532632,2.946992 6.872812,0.274872 13.746318,0.239142 20.619129,0 12.056067,-0.419493 14.904518,-1.281979 16.532631,-2.946992 1.628112,-1.665012 2.896267,-3.868352 3.163106,-9.787947 0.290219,-6.438226 0.22553,-11.81755 0,-16.717311 -0.272457,-5.91934 -1.534993,-8.124897 -3.163106,-9.789911 -1.628112,-1.665011 -4.476564,-2.525535 -16.532631,-2.945028 -3.436406,-0.11957 -6.874141,-0.188071 -10.310547,-0.192537 z"  fill="#ff0000"/>
                <path d="m 38.954235,36.296881 c -0.04114,0.01988 -0.07078,0.06698 -0.07216,0.150346 l 0.192443,16.923022 c 0.0031,0.08629 0.07085,0.147402 0.156361,0.106245 L 54.25552,44.862632 c 0.04435,-0.02648 0.0666,-0.109873 0.0021,-0.16037 L 39.102577,36.316927 c -0.05359,-0.03119 -0.107206,-0.03993 -0.148342,-0.02005 z" fill="white"/>
              </svg>
						</a>
						<a href="https://daria-orel.mave.digital" target="_blank" rel="noopener noreferrer" title="Подкаст «Истории с Дарьей Фурманской»">
							<svg class="svg-logo" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
								<path d="M 40,0 C 17.909493,0 0,17.90949 0,40 0,62.09051 17.909493,80 40,80 62.090512,80 80,62.09051 80,40 80,17.90949 62.090512,0 40,0 Z m -0.216797,11.019531 c 7.295388,0 13.263672,5.968285 13.263672,13.263672 v 10.734375 c 0,7.295387 -5.968286,13.263672 -13.263672,13.263672 -7.295387,0 -13.263672,-5.968285 -13.263672,-13.263672 V 24.283203 c 0,-7.295388 5.968284,-13.263672 13.263672,-13.263672 z m 0,5 c -3.702567,0 -6.803048,2.385954 -7.867187,5.716797 h 3.447265 a 2.5,2.5 0 0 1 2.5,2.5 2.5,2.5 0 0 1 -2.5,2.5 h -3.84375 v 5.734375 h 3.84375 a 2.5,2.5 0 0 1 2.5,2.5 2.5,2.5 0 0 1 -2.5,2.5 h -3.478515 c 1.035128,3.3803 4.160836,5.810547 7.898437,5.810547 3.737601,0 6.863309,-2.430247 7.898438,-5.810547 h -3.044922 a 2.5,2.5 0 0 1 -2.5,-2.5 2.5,2.5 0 0 1 2.5,-2.5 h 3.410156 v -5.734375 h -3.410156 a 2.5,2.5 0 0 1 -2.5,-2.5 2.5,2.5 0 0 1 2.5,-2.5 h 3.013672 C 46.586251,18.405485 43.48577,16.019531 39.783203,16.019531 Z M 18.677734,26.041016 a 2.5,2.5 0 0 1 2.5,2.5 v 6.287109 c 0,10.424849 8.397417,18.822266 18.822266,18.822266 10.424849,0 18.822266,-8.397416 18.822266,-18.822266 v -6.287109 a 2.5,2.5 0 0 1 2.5,-2.5 2.5,2.5 0 0 1 2.5,2.5 v 6.287109 c 0,12.380856 -9.514456,22.595183 -21.609375,23.716797 v 5.435547 h 8.167968 a 2.5,2.5 0 0 1 2.5,2.5 2.5,2.5 0 0 1 -2.5,2.5 H 28.988281 a 2.5,2.5 0 0 1 -2.5,-2.5 2.5,2.5 0 0 1 2.5,-2.5 h 8.22461 V 58.482422 C 25.392724,57.095763 16.177734,47.012163 16.177734,34.828125 v -6.287109 a 2.5,2.5 0 0 1 2.5,-2.5 z" />
								<g class="podcast-logo" fill="none" stroke-linecap="round" stroke-width="5">
									<path d="m 50.559243,35.105689 c 0,5.922477 -4.801118,10.723593 -10.723595,10.723593 -5.922477,0 -10.723595,-4.801116 -10.723595,-10.723593 V 24.410934 c 0,-5.922479 4.801116,-10.723595 10.723595,-10.723595 5.922479,0 10.723595,4.801116 10.723595,10.723595 z" />
									<path d="m 18.807376,28.652852 v 6.26427 c 0,11.732742 9.511266,21.24401 21.244007,21.24401 11.732741,0 21.244008,-9.511268 21.244008,-21.24401 v -6.26427" />
									<path d="M 29.080749,66.452378 H 50.393614" />
									<path d="m 39.765461,57.18706 v 8.343337" />
									<path d="M 35.431828,24.364779 H 30.087173" />
									<path d="M 35.431828,35.059541 H 30.087173" />
									<path d="M 49.043343,35.059541 H 44.670939" />
									<path d="M 49.043343,24.364779 H 44.670939" />
								</g>
							</svg>
						</a>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col copyr-container footer-links">
					<p>© 2015–2025 <a href="<?php echo esc_attr( home_url() ); ?>">«Экскурсии по Орлу»</a></p>
					<p>© Разработка <span class="adaptive-hide">и поддержка </span>сайта: <a href="https://github.com/denkiril" target="_blank" rel="noopener noreferrer">Денис Кирилюк</a></p>
					<p class="sub_copyr">Для замечаний и предложений по работе сайта: <a href="mailto:den-kiril@yandex.ru">den-kiril@yandex.ru</a></p>
					<!-- <p><a href="https://yasobe.ru/na/excursorelru" target="_blank" rel="noopener noreferrer">$ Поддержать проект</a></p> -->
					<p class="sub_copyr">Использование материалов сайта приветствуется, удаление копирайтов – нет.</p>
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
