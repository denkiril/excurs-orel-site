/* global VK */
/* global getApi */
/* global Glide */
/* global isInViewport */

const SocialSection = document.getElementById('soc-section');
const vkGroups = document.getElementById('vk_groups');
const fbRoot = document.getElementById('fb-root');
const $glide = document.querySelector('.glide');

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

// social widgets only for big screens
if (SocialSection && window.screen.width > 768) {
  SocialSection.style.display = 'block';
  window.addEventListener('load', socialInit);
}

// $(document).ready(() => {
//   // Слайдер-карусель
//   if ($('.carousel').length) { // anti "$(...).slick is not a function"
//     $('.carousel').slick({
//       arrows: false,
//       dots: true,
//       autoplay: (window.screen.width > 768),
//       autoplaySpeed: 5000,
//       lazyLoad: 'ondemand',
//       // lazyLoad: 'progressive'
//     });
//   }
// });

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
