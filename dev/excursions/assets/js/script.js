const NavBlock = document.getElementById('nav-block'); // ID шапки
const NavBlockTop = NavBlock.getBoundingClientRect().top + window.pageYOffset;
const Menu = document.getElementById('menu');
const NavMenu = document.querySelector('.nav-menu');
const Up = document.getElementById('up');
const yandexRtb = document.getElementById('yandex_rtb_R-A-414612-1');

let NavMenuHeight;
let menuStateOpen = false;
window.NavBlock = NavBlock;

/* global Ya */
/* global myajax */
/* exported ymapsApiUrl */
// eslint-disable-next-line no-unused-vars
// const ymapsApiUrl 	= 'https://api-maps.yandex.ru/2.1/?lang=ru_RU';
// const vkApiUrl 		= '//vk.com/js/api/openapi.js?160';
// const fbSdkUrl 		= 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1';
// window.ymapsApiUrl = ymapsApiUrl;


function getApi(apiname) {
  let apiUrl = null;
  let apikeyName = null;
  let paramName = null;
  switch (apiname) {
    case 'ymaps':
      apiUrl = 'https://api-maps.yandex.ru/2.1/?lang=ru_RU';
      apikeyName = window.location.hostname === 'excurs-orel.ru' ? 'YANDEX_MAPS_API' : null;
      paramName = 'apikey';
      break;
    case 'ycontext':
      apiUrl = '//an.yandex.ru/system/context.js';
      break;
    case 'vk':
      apiUrl = '//vk.com/js/api/openapi.js?160';
      break;
    case 'fb':
      apiUrl = 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1';
      // apiUrl = 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&autoLogAppEvents=1';
      apikeyName = null; // 'FB_APPID';
      paramName = 'appId';
      break;
    default: break;
  }

  const getApikeyParam = new Promise((resolve) => {
    if (apikeyName) {
      const requestUrl = `${myajax.url}?action=get_apikey`;
      // const requestUrl = `${myajax.url}?action=get_apikey&apikeyname=YANDEX_MAPS_API`;
      console.log(requestUrl);
      fetch(requestUrl, { headers: { 'content-type': 'application/x-www-form-urlencoded; charset=UTF-8' } })
        .then(response => response.json())
        .then((apikey) => {
          console.log('apikey:', apikey);
          const apikeyParam = apikey ? `&${paramName}=${apikey}` : '';
          resolve(apikeyParam);
        });
    } else {
      resolve('');
    }
  });

  return new Promise((resolve, reject) => {
    getApikeyParam
      .then((apikeyParam) => {
        const script = document.createElement('script');
        document.body.appendChild(script);
        script.onload = resolve;
        script.onerror = reject;
        script.async = true;
        script.src = apiUrl + apikeyParam;
      });
  });
}

window.getApi = getApi;

function isInViewport(el) {
  const rect = el.getBoundingClientRect();
  // console.log(rect);

  return (
    rect.bottom >= 0
    && rect.right >= 0
    && rect.top <= (window.innerHeight || document.documentElement.clientHeight)
    && rect.left <= (window.innerWidth || document.documentElement.clientWidth)
  );
}

window.isInViewport = isInViewport;

function init() {
  // Блок, работающий на JS, показываем только если работает JS
  const sb = document.getElementById('soc-buttons');
  if (sb) sb.style.display = 'block';

  // if (window.screen.width > 768 && Up) {
  //   Up.addEventListener('click', () => {
  //     document.body.scrollTop = 0; // For Safari
  //     document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  //   });
  // }
}

function setNavMenu() {
  if (window.screen.width <= 768) {
    NavMenu.style.display = 'block';
    NavMenuHeight = NavMenu.getBoundingClientRect().height;
    // console.log(NavMenuHeight);
    NavMenu.style.height = '0px';
  }
}

function watchResize() {
  if (window.screen.width > 768) {
    NavBlock.classList.remove('fixed');
  } else if (Up) {
    Up.style.display = 'none';
  }

  // if(NavBlock.classList.contains('menu_state_open')){}
  NavBlock.classList.remove('menu_state_open');
  NavMenu.removeAttribute('style');
  setNavMenu();
}

function watchScroll() {
  const scrollTop = window.pageYOffset;
  // console.log(`scrollTop = ${scrollTop}\n`);

  if (window.screen.width <= 768) {
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
  } else if (scrollTop > 500) {
    Up.style.display = 'block';
  } else {
    Up.style.display = 'none';
  }
}

window.addEventListener('load', init);
window.addEventListener('load', setNavMenu);
window.addEventListener('scroll', watchScroll);
window.addEventListener('resize', watchResize);

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

function scrollIt(destination, duration = 350) {
  const start = window.pageYOffset;
  const startTime = 'now' in window.performance ? performance.now() : new Date().getTime();
  const { body } = document;
  const dele = document.documentElement;

  // eslint-disable-next-line max-len
  const dHeight = Math.max(body.scrollHeight, body.offsetHeight, dele.clientHeight, dele.scrollHeight, dele.offsetHeight);
  const wHeight = window.innerHeight || dele.clientHeight || document.getElementsByTagName('body')[0].clientHeight;
  const dOffset = typeof destination === 'number' ? destination : destination.offsetTop;
  const dOffsetScroll = Math.round(dHeight - dOffset < wHeight ? dHeight - wHeight : dOffset);

  if ('requestAnimationFrame' in window === false) {
    window.scroll(0, dOffsetScroll);
    return;
  }

  function scroll() {
    const now = 'now' in window.performance ? performance.now() : new Date().getTime();
    const time = Math.min(1, ((now - startTime) / duration));
    const timeFunction = time;
    window.scroll(0, Math.ceil((timeFunction * (dOffsetScroll - start)) + start));

    // window.pageYOffset === dOffsetScroll || requestAnimationFrame(scroll);
    if (window.pageYOffset !== dOffsetScroll) { requestAnimationFrame(scroll); }
  }

  scroll();
}

// document.querySelectorAll('a').forEach((el) => {
[].forEach.call(document.querySelectorAll('a'), (el) => {
  const ahref = el.getAttribute('href');
  if (ahref && ahref.match(/^#/)) {
    el.addEventListener('click', (e) => {
      // console.log(e.target);
      console.log(e.currentTarget);
      const href = e.currentTarget.getAttribute('href');
      if (href && href.match(/^#/)) {
        e.preventDefault();
        if (href.match(/^#$/)) {
          scrollIt(0);
        } else {
          scrollIt(document.querySelector(href));
        }
      }
    });
  }
});

// <script type="text/javascript">
// (function(w, d, n, s, t) {
//   w[n] = w[n] || [];
//   w[n].push(function() {
//     Ya.Context.AdvManager.render({
//       blockId: "R-A-414612-1",
//       renderTo: "yandex_rtb_R-A-414612-1",
//       async: true
//     });
//   });
//   t = d.getElementsByTagName("script")[0];
//   s = d.createElement("script");
//   s.type = "text/javascript";
//   s.src = "//an.yandex.ru/system/context.js";
//   s.async = true;
//   t.parentNode.insertBefore(s, t);
// })(this, this.document, "yandexContextAsyncCallbacks");
// </script>

function yandexRtbMount() {
  const n = 'yandexContextAsyncCallbacks';
  window[n] = window[n] || [];
  window[n].push(() => {
    Ya.Context.AdvManager.render({
      blockId: 'R-A-414612-1',
      renderTo: 'yandex_rtb_R-A-414612-1',
      async: true,
    });
  });

  getApi('ycontext');

  // getScript('//an.yandex.ru/system/context.js').then(() => {
  //   const n = 'yandexContextAsyncCallbacks';
  //   this[n] = this[n] || [];
  //   // eslint-disable-next-line prefer-arrow-callback
  //   this[n].push(function yandexRtbRender() {
  //     Ya.Context.AdvManager.render({
  //       blockId: 'R-A-414612-1',
  //       renderTo: 'yandex_rtb_R-A-414612-1',
  //       async: true,
  //     });
  //   });
  // });
}

function yandexRtbLazyLoad() {
  console.log('yandexRtbLazyLoad');
  let inViewport = false;
  if (isInViewport(yandexRtb)) {
    yandexRtbMount();
    window.removeEventListener('scroll', yandexRtbLazyLoad);
    console.log('remove yandexRtbLazyLoad');
    inViewport = true;
  }

  return inViewport;
}

function yandexRtbInit() {
  if (!yandexRtbLazyLoad()) {
    console.log('add yandexRtbLazyLoad');
    window.addEventListener('scroll', yandexRtbLazyLoad);
  }
}

if (yandexRtb && window.screen.width > 768) {
  window.addEventListener('load', yandexRtbInit);
}
