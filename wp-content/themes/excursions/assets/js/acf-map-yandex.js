const OpenMapButton = document.querySelector('#OpenMap_btn');

registerListener('load', initOpenEventMap);

function OpenEventMap(){
  OpenMapButton.style.display = 'none';

  getScript(ymaps_api_url).then( () => {
    // Функция ymaps.ready() будет вызвана, когда загрузятся все компоненты API, а также когда будет готово DOM-дерево.
    ymaps.ready(acf_map_init);
  });

	// $('.acf-map').show();
	// $('#OpenMap_btn').hide();
	// [].forEach.call(document.querySelectorAll('.acf-map'), function(elem) {
	// 	elem.style.display = 'block';
  // });
}

function initOpenEventMap(){
  if(screen.width > 768){
    // console.log('screen.width > 768');

    // acf-map only for big screens
    OpenEventMap();
  }
  else{
    if( OpenMapButton ){
      OpenMapButton.style.display = 'block';
      // $('#OpenMap_btn').click(function() { OpenEventMap(); })
      OpenMapButton.addEventListener('click', OpenEventMap);
    }
  }
}

function acf_map_init(){ 

    function new_map( $el ) {

      // var $markers = $el.find('.marker');
      // var $markers = $el.querySelectorAll('.marker');

      const map = new ymaps.Map( $el, { // var ... $el[0]
        center: [0, 0],
        zoom: 16
      });
      
      map.behaviors.disable('scrollZoom');

      map.markers = [];
      
      // $markers.each(function(){ add_marker( $(this), map ); });
      [].forEach.call( $el.querySelectorAll('.marker'), function($marker){
        add_marker( $marker, map );
      });

      // center_map( map );
      // console.log(map.getZoom()+'\n');
      map.setBounds(
            map.geoObjects.getBounds(), 
            { checkZoomRange: true }
          ).then( function(){ 
            const zoom = map.getZoom();
            if(zoom < 12 || zoom > 16) map.setZoom(16); 
          });
      
      return map;
    }

    function add_marker( $marker, map ) {
  
      // var lat = $marker.attr('data-lat');
      // var lng = $marker.attr('data-lng');
      const lat = $marker.dataset.lat;
      const lng = $marker.dataset.lng;

      // Создание геообъекта с типом точка (метка)
      const marker = new ymaps.Placemark(
        [lat, lng],
        {},
        { 
          preset: 'islands#Icon',
          iconColor: '#005281', // 015a8d
        }
      );  

      // Размещение геообъекта на карте.
      map.geoObjects.add(marker);

      // add to array
      map.markers.push(marker);

    }

    // $('.acf-map').each(function(){ map = new_map( $(this) ); });
    [].forEach.call(document.querySelectorAll('.acf-map'), function(elem) {
      map = new_map(elem);
      elem.style.display = 'block';
    });
}