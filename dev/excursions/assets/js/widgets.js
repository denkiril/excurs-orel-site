const vkApiUrl 		= '//vk.com/js/api/openapi.js?160';
const fbSdkUrl 		= 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1';
const SocialSection = document.querySelector('#soc-section');
const vkGroups 		= document.querySelector('#vk_groups');
const fbRoot 		= document.querySelector('#fb-root');
const glideEl 	= document.querySelector('.glide');
/* global VK */
/* global getScript */
/* global registerListener */
/* global Glide */

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
          mode: vkGroups.getAttribute('data-mode') || 3,
          width: vkGroups.getAttribute('data-width') || 300,
          height: vkGroups.getAttribute('data-height') || 300,
          no_cover: vkGroups.getAttribute('data-no_cover') || 0,
        },
        vkGroups.getAttribute('data-id'),
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

registerListener('load', socialInit, (SocialSection && window.screen.width > 768)); // social widgets only for big screens

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

if (glideEl) {
  const glide = new Glide('.glide', {
    // type: 'carousel',
    autoplay: (window.screen.width > 768) ? 5000 : false,
  });

  glide.mount();
}
