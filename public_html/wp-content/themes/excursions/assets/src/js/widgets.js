/* global VK */
/* global getApi */
/* global Glide */
/* global isInViewport */

const SocialSection = document.getElementById('soc-section');
const vkGroups = document.getElementById('vk_groups');
const fbRoot = document.getElementById('fb-root');
const $glide = document.querySelector('.glide');
const ShowVideoBtn = document.querySelector('#ShowVideo_btn');

function socialMount() {
  if (vkGroups) {
    getApi('vk').then(() => {
      VK.Widgets.Group(
        'vk_groups',
        {
          mode: vkGroups.getAttribute('data-mode') || 3,
          width: vkGroups.getAttribute('data-width') || 300,
          height: vkGroups.getAttribute('data-height') || 300,
          no_cover: vkGroups.getAttribute('data-no_cover') || 0,
        },
        vkGroups.getAttribute('data-id'),
      );
    });
  }

  if (fbRoot) getApi('fb');
}

function socialLazyLoad() {
  // console.log('socialLazyLoad');
  let inViewport = false;
  if (isInViewport(SocialSection)) {
    socialMount();
    window.removeEventListener('scroll', socialLazyLoad);
    console.log('remove socialLazyLoad');
    inViewport = true;
  }

  return inViewport;
}

function socialInit() {
  if (!socialLazyLoad()) {
    console.log('add socialLazyLoad');
    window.addEventListener('scroll', socialLazyLoad);
  }
}

// Social widgets only for big screens...
if (SocialSection && window.screen.width > 768) {
  const script = document.createElement('script');
  const supportsModules = 'noModule' in script;
  // ...and for module-supports.
  if (supportsModules) {
    SocialSection.style.display = 'block';
    window.addEventListener('load', socialInit);
  }
}

if ($glide) {
  const $glideArrows = $glide.querySelector('.glide__arrows');
  if ($glideArrows) $glideArrows.style.display = 'block';
  const $glideBullets = $glide.querySelector('.glide__bullets');
  if ($glideBullets) $glideBullets.style.display = 'block';

  const glide = new Glide('.glide', {
    // type: 'carousel',
    autoplay: (window.screen.width > 768) ? 5000 : false,
  });

  glide.mount();
}

// ShowVideo
let lazy = [];

function iframeLazyLoad() {
  // console.log('iframeLoad ' + lazy.length);
  if (lazy.length) {
    // for(var i=0; i<lazy.length; i++){
    lazy.forEach((iframe) => {
      if (isInViewport(iframe)) {
        if (iframe.getAttribute('data-iframe_src')) {
          // iframe.src = iframe.getAttribute('data-iframe_src');
          iframe.setAttribute('src', iframe.getAttribute('data-iframe_src'));
          iframe.removeAttribute('data-iframe_src');
        }
      }
    });

    // iframeCleanLazy();
    lazy = Array.prototype.filter.call(lazy, l => l.getAttribute('data-iframe_src'));

    if (lazy.length === 0) {
      window.removeEventListener('scroll', iframeLazyLoad);
      window.removeEventListener('resize', iframeLazyLoad);
    }
  }
}

function ShowVideo() {
  // console.log('ShowVideo');
  window.addEventListener('scroll', iframeLazyLoad);
  window.addEventListener('resize', iframeLazyLoad);

  // bShowVideo = true;
  lazy.forEach((iframe) => {
    // iframe.style.display = 'inline';
    iframe.setAttribute('style', 'display: inline;');
  });

  if (ShowVideoBtn) {
    ShowVideoBtn.style.display = 'none';
  }

  iframeLazyLoad();
}

function initShowVideo() {
  lazy = document.querySelectorAll('iframe[data-iframe_src]');
  // console.log('Found ' + lazy.length + ' lazy iframes');

  if (lazy.length) {
    if (window.screen.width > 768 || !ShowVideoBtn) {
      ShowVideo();
    } else if (ShowVideoBtn) {
      ShowVideoBtn.style.display = 'block';
      ShowVideoBtn.addEventListener('click', ShowVideo);
    }
  }
}

window.addEventListener('load', initShowVideo);
