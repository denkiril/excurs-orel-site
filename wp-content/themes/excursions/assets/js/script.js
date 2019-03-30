function registerListener(event, func) {
	if (window.addEventListener) {
			window.addEventListener(event, func)
	} else {
			window.attachEvent('on' + event, func)
	}
}

registerListener('load', lazyLoad);
// registerListener('load', init);

function lazyLoad(){
	[].forEach.call(document.querySelectorAll('img[data-src]'), function(img) {
		// if(isInViewport(lazy[i])){
		img.setAttribute('src', img.getAttribute('data-src'));
		// img.onload = function() {
		img.removeAttribute('data-src');
		// };
	});

	[].forEach.call( document.querySelectorAll('img[data-srcset]'), function(img){
		img.setAttribute('srcset', img.getAttribute('data-srcset'));
		img.removeAttribute('data-srcset');
	});

	[].forEach.call( document.querySelectorAll('img[data-sizes]'), function(img){
		img.setAttribute('sizes', img.getAttribute('data-sizes'));
		img.removeAttribute('data-sizes');
	});
	
	[].forEach.call( document.querySelectorAll('source[data-srcset]'), function(source){
		source.setAttribute('srcset', source.getAttribute('data-srcset'));
		source.removeAttribute('data-srcset');
	});
}
	// 	$('img[data-src]').each(function(){
	// 		$(this).attr('src', $(this).attr('data-src'));
	// 		$(this).removeAttr('data-src');
	// });

	// $('img[data-srcset]').each(function(){
	// 		$(this).attr('srcset', $(this).attr('data-srcset'));
	// 		$(this).removeAttr('data-srcset');
	// });
	
	// $('img[data-sizes]').each(function(){
	// 				$(this).attr('sizes', $(this).attr('data-sizes'));
	// 				$(this).removeAttr('data-sizes');
	// });
	
	// $('source[data-srcset]').each(function(){
	// 				$(this).attr('srcset', $(this).attr('data-srcset'));
	// 				$(this).removeAttr('data-srcset');
	// });
// function init(){
// 	[].forEach.call( document.querySelectorAll('.soc-buttons'), function(elem){
// 		elem.style.display = 'block';
// 	});
// }

$(document).ready(function(){

	// global
	var no_events_map = true;	
	// Фиксированное меню при прокрутке страницы
	var NavBlock = $('#nav-block'); // ID шапки
	var NavBlockTop = NavBlock.offset().top;

	// Меню-гамбургер для адаптивной версии
	$('#menu').on('click', function() {
		// $(this).closest('.nav-container').toggleClass('menu_state_open');
		$(this).toggleClass('menu_state_open');
		$('.nav-menu').slideToggle(300, function() {
			if($(this).css('display') === 'none') {
				$(this).removeAttr('style');
			}
		});
	});

	$(window).resize(function() {
		if(screen.width > 768)
			NavBlock.removeClass('fixed');
		if($('#menu').hasClass('menu_state_open')){
			$('#menu').removeClass('menu_state_open');
			$('.nav-menu').removeAttr('style');      
		}
	});

	$(window).scroll(function() {
		if(screen.width <= 768) {
			
			if(NavBlock.hasClass('fixable') && $(window).scrollTop() > NavBlockTop) { // Тут идея в том, что блок привязывается к верху, как только "прилипает" к верху окна браузера. Замените $block.offset().top на любое значение и получится, что когда вы проскроллили на большее кол-во пиксейлей, чем указали, добавиться класс к шапке.
				NavBlock.addClass('fixed'); // Добавляем класс fixed. В стилях указываем для него position:fixed, height и прочее, чтобы сделать шапку фиксированной.
			} else {
				NavBlock.removeClass('fixed'); // удаляем класс
			}

			if($('#menu').hasClass('menu_state_open')){
				$('#menu').removeClass('menu_state_open');
				$('.nav-menu').slideUp(300, function() {
					if($(this).css('display') === 'none'){
						$(this).removeAttr('style');
					}
			});
			}
		}
	});

	$.ajaxSetup({
	  // cache: true
	});

	function OpenEventMap(){
		if( $('.acf-map').length ){
			// console.log('acf-map ok');
			// var api_url = "https://api-maps.yandex.ru/2.1/?apikey=6ebdbbc2-3779-4216-9d88-129e006559bd&lang=ru_RU";
			var api_url = "https://api-maps.yandex.ru/2.1/?lang=ru_RU";
			// $.getScript(api_url).then(function() {
			$.ajax({
				url: api_url,
				dataType: "script",
				// cache: true,
				// success: console.log('ymap-api ok')
			}).then(function() {
				// E:\OSPanel\domains\excurs-orel\wp-content\themes\excursions\assets\js\acf-map-yandex.js
				$.getScript("/wp-content/themes/excursions/assets/js/acf-map-yandex.js");
			});
		
			$('.acf-map').show();
			$('#OpenMap_btn').hide();
		}
	}

	$('#OpenMap_btn').click(function() {
		OpenEventMap();
	})

	// const events_map_sel = document.getElementsByClassName('events_map');

	// events_map_sel.forEach( function(events_map){
	$('.events_map').each(function(){
		var events_map = $(this);
		// var $emb_map = $(this).find('.events_block_map');
		// var $emb_filter = $(this).find('.events_block_filter');
		// var $emb_list = $(this).find('.events_block_list');
		var emb_panel = $('<div class="events_block events_block_panel"></div>');
		var emb_button = $('<button class="NewEventsMap_btn" data-state="open">[ Показать на карте ]</button>');
		emb_button.click( function() { ClickEventsMapBtn(events_map, $(this)); } )
		emb_panel.append(emb_button);
		events_map.append(emb_panel);
		// events_map.before(emb_panel);
	});

	var ClickEventsMapBtn = function(events_map, button){
			if( button.attr('data-state') == 'open' ){
					if( no_events_map ){
						var api_url = "https://api-maps.yandex.ru/2.1/?lang=ru_RU";
						// var events_map_js = "/wp-content/themes/excursions/assets/js/events_map.js";
						$.getScript(api_url).then(function(){
								// $.getScript(events_map_js);
							// }).then(function(){
								NewEventsMap(events_map);
								no_events_map = false;									
							});
					}

					button.attr('data-state', 'close');
					button.text('[Закрыть карту]');
					events_map.find('.events_map_content').show();
					NavBlock.removeClass('fixable');
			}
			else{
					button.attr('data-state', 'open');
					button.text('[Открыть карту]');
					events_map.find('.events_map_content').hide();
					NavBlock.addClass('fixable');
			}
	}

	// function OpenForm(){
		// var form = document.getElementById("reg_form");
		// if(form) form.style.display = "block";
		// var btn = document.getElementById("reg_btn");
		// if(btn) btn.style.display = "none";
	// }
	$('#reg_btn').click(function() {
		$(this).hide();
		$("#reg_form").slideDown();
	})
	
	// function CloseForm(){
	// 	var form = document.getElementById("reg_form");
	// 	if(form) form.style.display = "none";
	// 	var btn = document.getElementById("reg_btn");
	// 	if(btn) btn.style.display = "inline";
	// }
	$('#close_btn').click(function() {
		$("#reg_form").slideUp(400, function() {
			$('#reg_btn').show();
			// $('#reg_btn').removeAttribute('style');
		});
	})

	// Слайдер-карусель
	if( $('.carousel').length ){
		$('.carousel').slick({
			arrows: false,
			dots: true,
			autoplay: (screen.width > 768),
			autoplaySpeed: 5000,
			lazyLoad: 'ondemand'
			// lazyLoad: 'progressive'
		});
	}

	$(window).on('load', function() {
        // console.log('onload');
        


        // function styles_to_footer() {
        //     wp_enqueue_style( 'main-bottom-css', get_template_directory_uri() . '/assets/css/main-bottom.css' );
        //     wp_enqueue_style( 'main-font?family=Ubuntu:300,400&amp;subset=cyrillic', '//fonts.googleapis.com/css' );
        //     wp_enqueue_style( 'fancybox-css', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css' );
        // }
        // wp_enqueue_script( 'fancybox-js', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js', array('jquery'), false, 'in_footer' );

        // $('<link/>', {
        //     rel: 'stylesheet',
        //     type: 'text/css',
        //     href: '//fonts.googleapis.com/css?family=Ubuntu:300,400&amp;subset=cyrillic'
        // }).appendTo('head');

        // $('<link/>', {
        //     rel: 'stylesheet',
        //     type: 'text/css',
        //     href: '/wp-content/themes/excursions/assets/css/main-bottom.css'
        // }).appendTo('head');

        // fancybox
		// if( $('a[data-fancybox]').length ){
		// 	console.log('fancybox ok');
		// 	$('<link/>', {
		// 		rel: 'stylesheet',
		// 		type: 'text/css',
		// 		href: '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css'
        //     }).appendTo('head');
        //     $.getScript("//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js");
        // }



		
			// console.log('carousel ok');
			// $('<link/>', {
			// 	rel: 'stylesheet',
			// 	type: 'text/css',
			// 	href: '/wp-content/themes/excursions/assets/include/slick.css'
			// }).appendTo('head');
			// $('<link/>', {
			// 	rel: 'stylesheet',
			// 	type: 'text/css',
			// 	href: '/wp-content/themes/excursions/assets/include/slick-theme.css'
			// }).appendTo('head');
			// $.getScript("/wp-content/themes/excursions/assets/include/slick.min.js").then(function() {
                // $('.carousel').each(function(){
                    // $(this).children('img[data-src]').each(function(){
                    // $('img[data-src]').each(function(){
                    //     $(this).attr('src', $(this).attr('data-src'));
                    //     $(this).removeAttr('data-src');
                    // });
                // });
				
			// });

		if(screen.width > 768){
			// console.log('screen.width > 768');

			// acf-map only for big screens
			OpenEventMap();

			// social widgets only for big screens
			if( $('#soc-section').length ){
				// console.log('soc-section ok');

				// if( $('#vk_groups').length ){
					var vk_elem = $('#vk_groups');
					var mode = vk_elem.attr('data-mode') ? vk_elem.attr('data-mode') : 3;
					var width = vk_elem.attr('data-width') ? vk_elem.attr('data-width') : 300;
					var height = vk_elem.attr('data-height') ? vk_elem.attr('data-height') : 300;
					var no_cover = vk_elem.attr('data-no_cover') ? vk_elem.attr('data-no_cover') : 0;
					$.getScript("//vk.com/js/api/openapi.js?160").then(function() {
						VK.Widgets.Group("vk_groups", {
						mode: mode,
						width: width,
                        height: height,
                        no_cover: no_cover}, 
						vk_elem.attr('data-id'));
                    });
                    // console.log('no_cover: ' + no_cover);
				// }
				// if( $('.fb-group').length ){
					// <script async defer src="https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1"></script>
					// $.getScript("//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1");
				// }
				$('<script async defer src="https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1"></script>').insertAfter('#fb-root');

				$('#soc-section').show();
			}

		}

		// show #soc-buttons block only if .ya-share2 ok
		if( $('#soc-buttons').length ){ 
			$('<script async defer src="//yastatic.net/share2/share.js"></script>').insertAfter('#soc-buttons');
			$('#soc-buttons').show(); 
		}
		
	});



  // "Сворачиваем" форму после отправки
  // function HideForm(){
  //   $('#reg_form').children('.form-item').setAttribute('display', 'none');
  //   console.log('submit_ok');
  // }

});