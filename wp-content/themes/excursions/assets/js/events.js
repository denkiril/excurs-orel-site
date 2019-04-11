const ShowVideo_btn = document.querySelector('#ShowVideo_btn');

const reg_btn 	    = document.querySelector('#reg_btn');
const close_btn     = document.querySelector('#close_btn');
const reg_form 	    = document.querySelector('#reg_form');

// $('#reg_btn').click(function() { $(this).hide(); $("#reg_form").slideDown(); })
if(reg_btn){

	reg_btn.addEventListener('click', function() {
		this.style.display = 'none';
		reg_form.style.display = 'block'; // slideDown()
	});
}

// $('#close_btn').click(function() { $("#reg_form").slideUp(400, function() { $('#reg_btn').show(); }); })
if(close_btn){

	close_btn.addEventListener('click', function() {
		reg_form.style.display = 'none'; // slideUp
		reg_btn.style.display = 'inline';
	});
}

// ShowVideo 
// registerListener('load', initShowVideo);
window.addEventListener('load', initShowVideo);

let lazy = [];
// let bShowVideo = false;

function initShowVideo(){
    lazy = document.querySelectorAll('iframe[data-iframe_src]');
    // console.log('Found ' + lazy.length + ' lazy iframes');

    if(lazy.length){
      if( screen.width > 768 ){
        ShowVideo();
      }
      else if( ShowVideo_btn ){
        ShowVideo_btn.style.display = 'block';
        ShowVideo_btn.addEventListener('click', ShowVideo);
      }      
    }
} 

function iframeLazyLoad(){
  // console.log('iframeLoad ' + lazy.length);
  if(lazy.length){
    // for(var i=0; i<lazy.length; i++){
    lazy.forEach( function(iframe) {
        if(isInViewport(iframe)){
          if(iframe.getAttribute('data-iframe_src')){
            iframe.src = iframe.getAttribute('data-iframe_src');
            iframe.removeAttribute('data-iframe_src');
          }
        }
      });
      
    iframeCleanLazy();
  }
}

function iframeCleanLazy(){
    lazy = Array.prototype.filter.call(lazy, function(l){ return l.getAttribute('data-iframe_src');});

    if( lazy.length == 0 ){
      window.removeEventListener('scroll', iframeLazyLoad);
      window.removeEventListener('resize', iframeLazyLoad);
    }
}

function isInViewport(el){
    const rect = el.getBoundingClientRect();
    // console.log(rect);
    
    return (
        rect.bottom >= 0 && 
        rect.right >= 0 && 
        rect.top <= (window.innerHeight || document.documentElement.clientHeight) && 
        rect.left <= (window.innerWidth || document.documentElement.clientWidth)
     );
}

function ShowVideo(){

  // console.log('ShowVideo');
  window.addEventListener('scroll', iframeLazyLoad);
  window.addEventListener('resize', iframeLazyLoad);

  // bShowVideo = true;
  lazy.forEach( function(iframe) {
    iframe.style.display = 'inline';
  });

  if( ShowVideo_btn ){
    ShowVideo_btn.style.display = 'none';
  }

  iframeLazyLoad();
}

