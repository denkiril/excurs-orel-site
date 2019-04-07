function lazyLoad(){
	[].forEach.call(document.querySelectorAll('[data-src]'), function(img) {
		// if(isInViewport(lazy[i])){
		img.setAttribute('src', img.getAttribute('data-src'));
		// img.setAttribute('src', img.dataset.src);
		// img.onload = function() {
		img.removeAttribute('data-src');
		// };
	});

	[].forEach.call( document.querySelectorAll('[data-srcset]'), function(img){
        // img.setAttribute('srcset', img.dataset.srcset);
        img.setAttribute('srcset', img.getAttribute('data-srcset'));
		img.removeAttribute('data-srcset');
	});

	[].forEach.call( document.querySelectorAll('[data-sizes]'), function(img){
        // img.setAttribute('sizes', img.dataset.sizes);
        img.setAttribute('sizes', img.getAttribute('data-sizes'));
		img.removeAttribute('data-sizes');
	});
	
	// [].forEach.call( document.querySelectorAll('source[data-srcset]'), function(source){
	// 	source.setAttribute('srcset', source.getAttribute('data-srcset'));
	// 	source.removeAttribute('data-srcset');
	// });
}

window.addEventListener('load', lazyLoad);
