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
	$mypost_type = get_post_type();
	$no_ads_page = is_page( 'kvesty' ) || is_page( 'gid-v-orle' ) || is_page( 'map' );
	if ( 'events' !== $mypost_type && 'post' !== $mypost_type && ! $no_ads_page ) :
		?>
		<!-- Yandex.RTB R-A-414612-1 -->
		<div id="yandex_rtb_R-A-414612-1" style="padding: 20px 0 40px 0; min-height: 150px;"></div>

	<?php endif; ?>

	<?php if ( ! ( is_404() || is_search() || is_page( 'search' ) ) ) : ?>

		<div id="soc-buttons">
			<span>Поделиться: </span>
			<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter,viber,whatsapp,skype,telegram"></div>
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

					<a href="https://daria-orel.mave.digital" target="_blank" rel="noopener noreferrer" title="Подкаст «Истории с Дарьей Фурманской»">
						Подкаст про Орёл
					</a>

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
							<svg class="svg-logo vk-logo" viewBox="0 0 112.196 112.196" xmlns="http://www.w3.org/2000/svg">
								<path d="M 56.09375,0 C 25.11168,0 0,25.11168 0,56.09375 c 0,30.982069 25.11168,56.09375 56.09375,56.09375 30.982069,0 56.09375,-25.111681 56.09375,-56.09375 C 112.1875,25.11168 87.075819,0 56.09375,0 z m -5.5625,38.790959 c 2.668617,-0.02249 5.590416,0.0059 8,0.584041 3.628,0.876 2.40625,4.259 2.40625,12.375 0,2.598 -0.46875,6.25675 1.40625,7.46875 0.806,0.52 2.7555,0.09375 7.6875,-8.28125 2.338,-3.969 4.09375,-8.65625 4.09375,-8.65625 0.604885,-1.04735 1.174093,-1.385083 2.40625,-1.4375 L 87.625,40.78125 c 0,0 3.336,-0.40825 3.875,1.09375 0.565,1.577 -1.25625,5.2815 -5.78125,11.3125 -7.43,9.903 -8.26175,8.9625 -2.09375,14.6875 5.893,5.47 7.1125,8.13775 7.3125,8.46875 2.44,4.046 -2.6875,4.34375 -2.6875,4.34375 l -9.84375,0.15625 c 0,0 -2.12425,0.411 -4.90625,-1.5 C 69.821,76.81875 66.35925,70.268 63.65625,71.125 60.91425,71.995 61,77.875 61,77.875 c 0,0 0.164557,1.420602 -0.59375,1.9375 -1.906633,1.299652 -3.965857,1.516985 -6.605582,1.547328 -2.265085,0.02604 -9.322671,-0.113691 -18.081918,-8.984828 -9.463809,-9.584699 -17.59375,-29 -17.59375,-29 -0.359652,-1.84807 0.286392,-2.365291 2.21875,-2.59375 L 30.875,40.71875 c 1.772185,0.05127 1.912193,0.752733 2.59375,1.90625 0,0 1.71575,4.2955 3.96875,8.1875 4.397,7.598 6.4445,9.28275 7.9375,8.46875 2.175,-1.187 1.53125,-10.75 1.53125,-10.75 0,0 0.04125,-3.456 -1.09375,-5 -0.879,-1.197 -2.54925,-1.55825 -3.28125,-1.65625 -0.592,-0.079 0.365,-1.4455 1.625,-2.0625 1.4205,-0.696 3.67797,-0.99881 6.375,-1.021541 z" />
							</svg>						
						</a>
						<a href="https://www.facebook.com/groups/excursorel" target="_blank" rel="noopener noreferrer" title="Мы в фейсбуке">
							<svg class="svg-logo fb-logo" viewBox="0 0 112.196 112.196" xmlns="http://www.w3.org/2000/svg">
								<path d="M 56.09375 0 C 25.111681 6.2265008e-015 -8.6798001e-017 25.111681 0 56.09375 C 0 87.075819 25.111681 112.1875 56.09375 112.1875 C 87.075819 112.1875 112.1875 87.075819 112.1875 56.09375 C 112.1875 25.111681 87.075819 -1.2462122e-014 56.09375 0 z M 60.3125 21.75 L 71.5625 21.8125 L 71.5625 34.3125 L 63.40625 34.3125 C 62.06925 34.3125 60.1875 34.99875 60.1875 37.84375 L 60.1875 45.40625 L 71.53125 45.40625 L 70.1875 58.28125 L 60.1875 58.28125 L 60.1875 94.96875 L 45.03125 94.96875 L 45.03125 58.28125 L 37.8125 58.28125 L 37.8125 45.40625 L 45.03125 45.40625 L 45.03125 37.0625 C 45.03125 31.0985 47.8445 21.75 60.3125 21.75 z " />
							</svg>
						</a>
						<a href="https://www.instagram.com/excurs_orel/" target="_blank" rel="noopener noreferrer" title="Мы в инстаграме">
							<svg class="svg-logo ig-logo" viewBox="0 0 89.758 89.758" xmlns="http://www.w3.org/2000/svg">
								<path d="M 58.255,23.88 H 31.503 c -4.27,0 -7.744,3.474 -7.744,7.744 v 26.752 c 0,4.27 3.474,7.745 7.744,7.745 h 26.752 c 4.27,0 7.745,-3.474 7.745,-7.745 V 31.624 C 66,27.354 62.526,23.88 58.255,23.88 z M 44.879,58.906 c -7.667,0 -13.905,-6.238 -13.905,-13.906 0,-7.667 6.238,-13.905 13.905,-13.905 7.668,0 13.906,6.238 13.906,13.905 0,7.667 -6.239,13.906 -13.906,13.906 z M 59.232,33.97 c -1.815,0 -3.291,-1.476 -3.291,-3.29 0,-1.814 1.476,-3.29 3.291,-3.29 1.814,0 3.29,1.476 3.29,3.29 0,1.814 -1.476,3.29 -3.29,3.29 z" />
								<path d="m 44.879,36.971 c -4.426,0 -8.03,3.602 -8.03,8.028 0,4.428 3.604,8.031 8.03,8.031 4.428,0 8.029,-3.603 8.029,-8.031 0,-4.425 -3.602,-8.028 -8.029,-8.028 z" />
								<path d="M 44.879,0 C 20.094,0 0,20.094 0,44.879 0,69.664 20.094,89.758 44.879,89.758 69.664,89.758 89.758,69.664 89.758,44.879 89.758,20.094 69.664,0 44.879,0 z m 26.996,58.376 c 0,7.511 -6.109,13.62 -13.62,13.62 H 31.503 c -7.51,0 -13.62,-6.109 -13.62,-13.62 V 31.624 c 0,-7.51 6.11,-13.62 13.62,-13.62 h 26.752 c 7.511,0 13.62,6.11 13.62,13.62 v 26.752 z" />
							</svg>
						</a>
						<a href="https://www.youtube.com/channel/UCmPLUt16GHq_o4vC0AMY4rA" target="_blank" rel="noopener noreferrer" title="Ютуб-канал Дарьи Фурманской">
							<svg class="svg-logo" viewBox="0 0 89.758 89.758" xmlns="http://www.w3.org/2000/svg">
								<path d="m 38.954235,36.296881 c -0.04114,0.01988 -0.07078,0.06698 -0.07216,0.150346 l 0.192443,16.923022 c 0.0031,0.08629 0.07085,0.147402 0.156361,0.106245 L 54.25552,44.862632 c 0.04435,-0.02648 0.0666,-0.109873 0.0021,-0.16037 L 39.102577,36.316927 c -0.05359,-0.03119 -0.107206,-0.03993 -0.148342,-0.02005 z" />
								<path d="M 44.878906,1.884e-4 C 20.09391,1.884e-4 0,20.094094 0,44.879094 0,69.664094 20.09391,89.758 44.878906,89.758 c 24.785,0 44.878906,-20.093906 44.878906,-44.878906 0,-24.785 -20.093906,-44.8789056 -44.878906,-44.8789056 z m 0.25586,23.5449216 c 3.416226,0.0044 6.833774,0.07254 10.25,0.191406 11.98527,0.41703 14.816996,1.2725 16.435546,2.927734 1.618553,1.655237 2.873675,3.847842 3.144532,9.732422 0.224205,4.870988 0.288514,10.218723 0,16.619141 -0.265272,5.884834 -1.52598,8.075235 -3.144532,9.730469 -1.618552,1.655236 -4.450276,2.512657 -16.435546,2.929687 -6.832452,0.237737 -13.665594,0.273258 -20.498047,0 -11.982916,-0.479246 -14.816996,-1.274451 -16.435547,-2.929687 -1.618553,-1.655234 -2.824368,-3.848336 -3.144531,-9.730469 -0.331423,-6.089024 -0.437125,-11.803768 0,-16.619141 0.53258,-5.86689 1.525978,-8.077186 3.144531,-9.732422 1.618551,-1.655234 4.452631,-2.448488 16.435547,-2.927734 3.416226,-0.136626 6.83182,-0.195806 10.248047,-0.191406 z" />
								<path class="yt-logo" d="m 45.135047,23.419924 c -3.436407,-0.0044 -6.872176,0.0551 -10.308582,0.192537 -12.053698,0.482077 -14.904519,1.280017 -16.532632,2.945028 -1.628114,1.665013 -2.62738,3.888366 -3.163106,9.789911 -0.439707,4.843818 -0.33338,10.592319 0,16.717311 0.322055,5.916878 1.534992,8.122935 3.163106,9.787947 1.628113,1.665013 4.478934,2.464915 16.532632,2.946992 6.872812,0.274872 13.746318,0.239142 20.619129,0 12.056067,-0.419493 14.904518,-1.281979 16.532631,-2.946992 1.628112,-1.665012 2.896267,-3.868352 3.163106,-9.787947 0.290219,-6.438226 0.22553,-11.81755 0,-16.717311 -0.272457,-5.91934 -1.534993,-8.124897 -3.163106,-9.789911 -1.628112,-1.665011 -4.476564,-2.525535 -16.532631,-2.945028 -3.436406,-0.11957 -6.874141,-0.188071 -10.310547,-0.192537 z m -5.874456,13.067748 14.852987,8.219892 c 0.06331,0.04949 0.04155,0.130064 -0.0019,0.156017 l -14.724369,8.441995 c -0.0838,0.04034 -0.151287,-0.0186 -0.154293,-0.103167 l -0.187552,-16.58569 c 0.0027,-0.163402 0.110103,-0.190196 0.215138,-0.129047 z" />
							</svg>
						</a>
						<a href="https://daria-orel.mave.digital" target="_blank" rel="noopener noreferrer" title="Подкаст «Истории с Дарьей Фурманской»">
							<svg class="svg-logo podcast-logo" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
								<path d="M 40,0 C 17.909493,0 0,17.90949 0,40 0,62.09051 17.909493,80 40,80 62.090512,80 80,62.09051 80,40 80,17.90949 62.090512,0 40,0 Z m -0.216797,11.019531 c 7.295388,0 13.263672,5.968285 13.263672,13.263672 v 10.734375 c 0,7.295387 -5.968286,13.263672 -13.263672,13.263672 -7.295387,0 -13.263672,-5.968285 -13.263672,-13.263672 V 24.283203 c 0,-7.295388 5.968284,-13.263672 13.263672,-13.263672 z m 0,5 c -3.702567,0 -6.803048,2.385954 -7.867187,5.716797 h 3.447265 a 2.5,2.5 0 0 1 2.5,2.5 2.5,2.5 0 0 1 -2.5,2.5 h -3.84375 v 5.734375 h 3.84375 a 2.5,2.5 0 0 1 2.5,2.5 2.5,2.5 0 0 1 -2.5,2.5 h -3.478515 c 1.035128,3.3803 4.160836,5.810547 7.898437,5.810547 3.737601,0 6.863309,-2.430247 7.898438,-5.810547 h -3.044922 a 2.5,2.5 0 0 1 -2.5,-2.5 2.5,2.5 0 0 1 2.5,-2.5 h 3.410156 v -5.734375 h -3.410156 a 2.5,2.5 0 0 1 -2.5,-2.5 2.5,2.5 0 0 1 2.5,-2.5 h 3.013672 C 46.586251,18.405485 43.48577,16.019531 39.783203,16.019531 Z M 18.677734,26.041016 a 2.5,2.5 0 0 1 2.5,2.5 v 6.287109 c 0,10.424849 8.397417,18.822266 18.822266,18.822266 10.424849,0 18.822266,-8.397416 18.822266,-18.822266 v -6.287109 a 2.5,2.5 0 0 1 2.5,-2.5 2.5,2.5 0 0 1 2.5,2.5 v 6.287109 c 0,12.380856 -9.514456,22.595183 -21.609375,23.716797 v 5.435547 h 8.167968 a 2.5,2.5 0 0 1 2.5,2.5 2.5,2.5 0 0 1 -2.5,2.5 H 28.988281 a 2.5,2.5 0 0 1 -2.5,-2.5 2.5,2.5 0 0 1 2.5,-2.5 h 8.22461 V 58.482422 C 25.392724,57.095763 16.177734,47.012163 16.177734,34.828125 v -6.287109 a 2.5,2.5 0 0 1 2.5,-2.5 z" />
							</svg>
						</a>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col copyr-container footer-links">
					<p>© 2015–2022 <a href="<?php echo esc_attr( home_url() ); ?>">«Экскурсии по Орлу»</a></p>
					<p>© Разработка <span class="adaptive-hide">и поддержка </span>сайта: <a href="https://github.com/denkiril" target="_blank" rel="noopener noreferrer">Денис Кирилюк</a></p>
					<p><a href="https://yasobe.ru/na/excursorelru" target="_blank" rel="noopener noreferrer">$ Поддержать проект</a></p>
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
