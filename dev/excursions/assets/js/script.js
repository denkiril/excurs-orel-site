const NavBlock 		= document.querySelector('#nav-block'); // ID шапки
const NavBlockTop 	= NavBlock.getBoundingClientRect().top + window.pageYOffset;
const Menu 			= document.querySelector('#menu');
const NavMenu 		= document.querySelector('.nav-menu');
const SocialSection = document.querySelector('#soc-section');
const vkGroups 		= document.querySelector('#vk_groups');
const fbRoot 		= document.querySelector('#fb-root');

let NavMenuHeight;
let menuStateOpen = false;

/* exported ymapsApiUrl */
// eslint-disable-next-line no-unused-vars
const ymapsApiUrl 	= 'https://api-maps.yandex.ru/2.1/?lang=ru_RU';
const vkApiUrl 		= '//vk.com/js/api/openapi.js?160';
const fbSdkUrl 		= 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1';
/* global VK */

function getScript(src) {
  const scriptPromise = new Promise((resolve, reject) => {
    const script = document.createElement('script');
    document.body.appendChild(script);
    script.onload = resolve;
    script.onerror = reject;
    script.async = true;
    script.src = src;
  });

  return scriptPromise;
}

function registerListener(event, func, condition = true) {
  if (condition) {
    // if (window.addEventListener) {
    window.addEventListener(event, func);
    // } else {
    // 		window.attachEvent('on' + event, func)
  }
}

function init() {
  // Блок, работающий на JS, показываем только если работает JS
  const sb = document.getElementById('soc-buttons');
  if (sb) sb.style.display = 'block';
}

function setNavMenu() {
  if (window.screen.width <= 768) {
    NavMenu.style.display = 'block';
    NavMenuHeight = NavMenu.getBoundingClientRect().height;
    // console.log(NavMenuHeight);
    NavMenu.style.height = '0px';
  }
}

function socialInit() {
  // if(SocialSection && screen.width > 768){
  if (vkGroups) {
    // const mode 		= vk_groups.dataset.mode 	 || 3; 		//? vk_groups.dataset.mode 	  : 3;
    // const width 	= vk_groups.dataset.width 	 || 300; 	//? vk_groups.dataset.width 	  : 300;
    // const height 	= vk_groups.dataset.height 	 || 300; 	//? vk_groups.dataset.height   : 300;
    // const no_cover 	= vk_groups.dataset.no_cover || 0; 		//? vk_groups.dataset.no_cover : 0;
    // console.log(mode+' '+width+' '+height+' '+no_cover);

    // $.getScript("//vk.com/js/api/openapi.js?160").then(function() {
    getScript(vkApiUrl).then(() => {
      VK.Widgets.Group(
        'vk_groups',
        {
          mode: vkGroups.dataset.mode 			|| 3,
          width: vkGroups.dataset.width 		|| 300,
          height: vkGroups.dataset.height 		|| 300,
          no_cover: vkGroups.dataset.no_cover 	|| 0,
        },
        vkGroups.dataset.id,
      );
    });
  }

  if (fbRoot) {
    // $('<script async defer src="https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1"></script>').insertAfter('#fb-root');
    getScript(fbSdkUrl);
  }

  if (vkGroups || fbRoot) {
    SocialSection.style.display = 'block';
  }
}

function resizeNavBlock() {
  if (window.screen.width > 768) {
    NavBlock.classList.remove('fixed');
  }

  // if(NavBlock.classList.contains('menu_state_open')){}
  NavBlock.classList.remove('menu_state_open');
  NavMenu.removeAttribute('style');
  setNavMenu();
}

function scrollNavBlock() {
  if (window.screen.width <= 768) {
    const scrollTop = window.pageYOffset;
    if (NavBlock.classList.contains('fixable') && scrollTop > NavBlockTop) {
      NavBlock.classList.add('fixed');
    } else {
      NavBlock.classList.remove('fixed');
    }

    if (menuStateOpen) {
      menuStateOpen = false;
      NavBlock.classList.remove('menu_state_open');
      NavMenu.style.height = '0px';
    }
  }
}

registerListener('load', init);
registerListener('load', setNavMenu);
registerListener('load', socialInit, (SocialSection && window.screen.width > 768)); // social widgets only for big screens
registerListener('scroll', scrollNavBlock);
registerListener('resize', resizeNavBlock);

// Меню-гамбургер для адаптивной версии --- slideToggle
Menu.addEventListener('click', () => {
  menuStateOpen = !menuStateOpen;

  if (menuStateOpen) {
    NavBlock.classList.add('menu_state_open');
    NavMenu.style.height = `${NavMenuHeight}px`;
  } else {
    NavBlock.classList.remove('menu_state_open');
    NavMenu.style.height = '0px';
  }
});

$(document).ready(() => {
  // Слайдер-карусель
  if ($('.carousel').length) { // anti "$(...).slick is not a function"
    $('.carousel').slick({
      arrows: false,
      dots: true,
      autoplay: (window.screen.width > 768),
      autoplaySpeed: 5000,
      lazyLoad: 'ondemand',
      // lazyLoad: 'progressive'
    });
  }
});
