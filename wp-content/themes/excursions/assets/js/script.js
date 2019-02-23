// (function($){
//     $(function() {
//       $('#menu').on('click', function() {
//         $(this).closest('.nav-container').toggleClass('menu_state_open');
//       });
//     });
// })(jQuery);

$(document).ready(function(){

  // Фиксированное меню при прокрутке страницы
  var NavBlock = $('#nav-block'); // ID шапки
  var NavBlockTop = NavBlock.offset().top;

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
        if($(window).scrollTop() > NavBlockTop) { // Тут идея в том, что блок привязывается к верху, как только "прилипает" к верху окна браузера. Замените $block.offset().top на любое значение и получится, что когда вы проскроллили на большее кол-во пиксейлей, чем указали, добавиться класс к шапке.
            NavBlock.addClass('fixed'); // Добавляем класс fixed. В стилях указываем для него position:fixed, height и прочее, чтобы сделать шапку фиксированной.
        } else {
            NavBlock.removeClass('fixed'); // удаляем класс
        }

        if($('#menu').hasClass('menu_state_open')){
          $('#menu').removeClass('menu_state_open');
          $('.nav-menu').slideUp(300, function() {
            if($(this).css('display') === 'none') {
              $(this).removeAttr('style');
            }
          });
        }
      }
    });

  // lazyLoad images
  [].forEach.call(document.querySelectorAll('img[data-src]'), function(img) {
    img.setAttribute('src', img.getAttribute('data-src'));
    img.onload = function() {
    img.removeAttribute('data-src');
    };
  });

  // Слайдер-карусель
  $('.main-carousel').slick({
    arrows: false,
    dots: true,
    // autoplay: true,
    autoplaySpeed: 5000,
    lazyLoad: 'ondemand'
    // lazyLoad: 'progressive'
  });

  
	$(window).on('load', function() {
		if(screen.width > 768){
			// social widgets only for big screens
			if( $('#soc-section').length ){
				console.log('soc-section ok');
				$.getScript("//vk.com/js/api/openapi.js?160").then(function() {
					VK.Widgets.Group("vk_groups", {
					mode: 3,
					width: "300",
					height: "400"}, 
					94410363);
				});
				$('#soc-section').removeAttr('style');		
			}

			// autoplay only for big screens
			$('.main-carousel').slick('slickPlay');
		}
	});

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

  // "Сворачиваем" форму после отправки
  // function HideForm(){
  //   $('#reg_form').children('.form-item').setAttribute('display', 'none');
  //   console.log('submit_ok');
  // }

});