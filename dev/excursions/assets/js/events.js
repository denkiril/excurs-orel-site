import '../css/events.css';

const ShowVideoBtn = document.querySelector('#ShowVideo_btn');

const regBtn = document.querySelector('#reg_btn');
const closeBtn = document.querySelector('#close_btn');
const regForm = document.querySelector('#reg_form');
/* global isInViewport */

// $('#regBtn').click(function() { $(this).hide(); $("#reg_form").slideDown(); })
if (regBtn) {
  regBtn.addEventListener('click', function showRegForm() {
    this.style.display = 'none';
    regForm.style.display = 'block'; // slideDown()
  });
}

if (closeBtn) {
  closeBtn.addEventListener('click', () => {
    regForm.style.display = 'none'; // slideUp
    regBtn.style.display = 'inline';
  });
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
    if (window.screen.width > 768) {
      ShowVideo();
    } else if (ShowVideoBtn) {
      ShowVideoBtn.style.display = 'block';
      ShowVideoBtn.addEventListener('click', ShowVideo);
    }
  }
}

window.addEventListener('load', initShowVideo);
