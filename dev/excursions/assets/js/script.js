// require('@babel/polyfill');

const NavBlock 		= document.querySelector('#nav-block'); // ID шапки
const NavBlockTop 	= NavBlock.getBoundingClientRect().top + window.pageYOffset;
const Menu 			= document.querySelector('#menu');
const NavMenu 		= document.querySelector('.nav-menu');

let NavMenuHeight;
let menuStateOpen = false;

/* exported ymapsApiUrl */
// eslint-disable-next-line no-unused-vars
const ymapsApiUrl 	= 'https://api-maps.yandex.ru/2.1/?lang=ru_RU';

window.NavBlock = NavBlock;
window.ymapsApiUrl = ymapsApiUrl;

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

window.getScript = getScript;

function registerListener(event, func, condition = true) {
  if (condition) {
    // if (window.addEventListener) {
    window.addEventListener(event, func);
    // } else {
    // 		window.attachEvent('on' + event, func)
  }
}

window.registerListener = registerListener;

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

function lazyLoad() {
  [].forEach.call(document.querySelectorAll('[data-src]'), (img) => {
    // if(isInViewport(lazy[i])){
    img.setAttribute('src', img.getAttribute('data-src'));
    // img.setAttribute('src', img.dataset.src);
    // img.onload = function() {
    img.removeAttribute('data-src');
    // };
  });

  [].forEach.call(document.querySelectorAll('[data-srcset]'), (img) => {
    // img.setAttribute('srcset', img.dataset.srcset);
    img.setAttribute('srcset', img.getAttribute('data-srcset'));
    img.removeAttribute('data-srcset');
  });

  [].forEach.call(document.querySelectorAll('[data-sizes]'), (img) => {
    // img.setAttribute('sizes', img.dataset.sizes);
    img.setAttribute('sizes', img.getAttribute('data-sizes'));
    img.removeAttribute('data-sizes');
  });
}

window.addEventListener('load', lazyLoad);
