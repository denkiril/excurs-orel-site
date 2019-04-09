const NavBlock 		= document.querySelector('#nav-block'); // ID шапки 
const NavBlockTop 	= NavBlock.getBoundingClientRect().top + window.pageYOffset;
const Menu 			= document.querySelector('#menu');
const NavMenu 		= document.querySelector('.nav-menu');
const SocialSection = document.querySelector('#soc-section');
const vk_groups 	= document.querySelector('#vk_groups');
const fb_root 		= document.querySelector('#fb-root');

var NavMenuHeight;
var menu_state_open = false;

const ymaps_api_url = 'https://api-maps.yandex.ru/2.1/?lang=ru_RU';
const vk_api_url 	= '//vk.com/js/api/openapi.js?160';
const fb_sdk_url 	= 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1';


function getScript(src){
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

function registerListener(event, func, condition=true) {
	if (condition) {
	// if (window.addEventListener) {
			window.addEventListener(event, func);
	// } else {
	// 		window.attachEvent('on' + event, func)
	}
}

// registerListener('load', lazyLoad);
registerListener('load', init);
registerListener('load', setNavMenu);
registerListener('load', social_init, (SocialSection && screen.width > 768)); // social widgets only for big screens
registerListener('scroll', scrollNavBlock); 
registerListener('resize', resizeNavBlock);


function init(){
	// Блок, работающий на JS, показываем только если работает JS 
	const sb = document.getElementById('soc-buttons');
	if(sb) sb.style.display = 'block';
}

function setNavMenu(){
	if(screen.width <= 768) {
		NavMenu.style.display = 'block';
		NavMenuHeight = NavMenu.getBoundingClientRect().height;
		// console.log(NavMenuHeight);
		NavMenu.style.height = '0px';
	}
}

function social_init(){
	// if(SocialSection && screen.width > 768){
	if(vk_groups){
		// const mode 		= vk_groups.dataset.mode 	 || 3; 		//? vk_groups.dataset.mode 	  : 3;
		// const width 	= vk_groups.dataset.width 	 || 300; 	//? vk_groups.dataset.width 	  : 300;
		// const height 	= vk_groups.dataset.height 	 || 300; 	//? vk_groups.dataset.height   : 300;
		// const no_cover 	= vk_groups.dataset.no_cover || 0; 		//? vk_groups.dataset.no_cover : 0;
		// console.log(mode+' '+width+' '+height+' '+no_cover);

		// $.getScript("//vk.com/js/api/openapi.js?160").then(function() {
		getScript(vk_api_url).then( () => {
			VK.Widgets.Group(
				'vk_groups', 
				{
					mode: 		vk_groups.dataset.mode 		|| 3,
					width: 		vk_groups.dataset.width 	|| 300,
					height: 	vk_groups.dataset.height 	|| 300,
					no_cover: 	vk_groups.dataset.no_cover 	|| 0
				}, 
				vk_groups.dataset.id
			);
		});
	}
	
	if(fb_root){
		// $('<script async defer src="https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1"></script>').insertAfter('#fb-root');
		getScript(fb_sdk_url);
	}

	if(vk_groups || fb_root){
		SocialSection.style.display = 'block';
	}
}

function resizeNavBlock(){
	if(screen.width > 768){
		NavBlock.classList.remove("fixed");
	}

	// if(NavBlock.classList.contains('menu_state_open')){}
	NavBlock.classList.remove('menu_state_open');
	NavMenu.removeAttribute('style');
	setNavMenu();
}

function scrollNavBlock(){
	if(screen.width <= 768) {
		const scrollTop = window.pageYOffset; 
		// const scrollTop = window.pageYOffset || document.documentElement.scrollTop; // 2-ой вариант - для IE8-
		if(NavBlock.classList.contains('fixable') && scrollTop > NavBlockTop) {
			NavBlock.classList.add('fixed');
		} else {
			NavBlock.classList.remove('fixed');
		}

		if(menu_state_open){
			menu_state_open = false;
			NavBlock.classList.remove('menu_state_open');
			NavMenu.style.height = '0px';
		}
	}
}

// Меню-гамбургер для адаптивной версии --- slideToggle
Menu.addEventListener('click', function() {
	menu_state_open = !menu_state_open;

	if(menu_state_open){
		NavBlock.classList.add("menu_state_open");
		NavMenu.style.height = NavMenuHeight + 'px';
	}
	else{
		NavBlock.classList.remove("menu_state_open");
		NavMenu.style.height = '0px';
	}
});

$(document).ready(function(){

	// function OpenForm(){
		// var form = document.getElementById("reg_form");
		// if(form) form.style.display = "block";
		// var btn = document.getElementById("reg_btn");
		// if(btn) btn.style.display = "none";
	// }
	
	// function CloseForm(){
	// 	var form = document.getElementById("reg_form");
	// 	if(form) form.style.display = "none";
	// 	var btn = document.getElementById("reg_btn");
	// 	if(btn) btn.style.display = "inline";
	// }

	// Слайдер-карусель
	if( $('.carousel').length ){ // anti "$(...).slick is not a function"
		$('.carousel').slick({
			arrows: false,
			dots: true,
			autoplay: (screen.width > 768),
			autoplaySpeed: 5000,
			lazyLoad: 'ondemand'
			// lazyLoad: 'progressive'
		});
	}
});