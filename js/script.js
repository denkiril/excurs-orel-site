// (function($){
//     $(function() {
//       $('.menu-icon').on('click', function() {
//         $(this).closest('.nav-container').toggleClass('menu_state_open');
//       });
//     });
// })(jQuery);

$(document).ready(function(){

  // Слайдер-карусель
  $('.main-carousel').slick({
    arrows: false,
    dots: true,
    // autoplay: true,
    autoplaySpeed: 5000
    // lazyLoad: 'ondemand',
    // lazyLoad: 'progressive'
  });

  // autoplay only for big screens
  if(screen.width > 768)
    $('.main-carousel').slick('slickPlay');

  // Меню-гамбургер для адаптивной версии
  $('.menu-icon').on('click', function() {
    $(this).closest('.nav-container').toggleClass('menu_state_open');
  });

});