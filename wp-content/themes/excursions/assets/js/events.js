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
registerListener('load', initShowVideo);

function initShowVideo(){
    if(screen.width > 768){
      // console.log('screen.width > 768');
  
      // acf-map only for big screens
      ShowVideo();
    }
    else{
      if( ShowVideo_btn ){
        ShowVideo_btn.style.display = 'block';
        ShowVideo_btn.addEventListener('click', ShowVideo);
      }
    }
}

function ShowVideo(){

    [].forEach.call( document.querySelectorAll('iframe[data-iframe_src]'), function(iframe){
        // iframe.setAttribute('srcset', iframe.getAttribute('data-srcset'));
        iframe.setAttribute('src', iframe.dataset.iframe_src);
        iframe.removeAttribute('data-iframe_src');
        iframe.style.display = 'inline';
	});

    this.style.display = 'none';
}