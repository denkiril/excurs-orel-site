// Функция ymaps.ready() будет вызвана, когда
// загрузятся все компоненты API, а также когда будет готово DOM-дерево.
ymaps.ready(init);

function init(){ 

    function new_map( $el ) {

      var $markers = $el.find('.marker');

      var map = new ymaps.Map( $el[0], {
        center: [0, 0],
        zoom: 16
      });
      
      map.behaviors.disable('scrollZoom');

      // map.markers = [];
      
      $markers.each(function(){
          add_marker( $(this), map );
      });

      // center_map( map );
      map.setBounds(map.geoObjects.getBounds(), 
        {checkZoomRange:true}).then(function(){ if(map.getZoom() > 16) map.setZoom(16); });

      return map;
    }

    function add_marker( $marker, map ) {
  
      var lat = $marker.attr('data-lat');
      var lng = $marker.attr('data-lng');

      // Создание геообъекта с типом точка (метка)
      var marker = new ymaps.Placemark(
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
      // map.markers.push(marker);

    }

    $('.acf-map').each(function(){
  
      // create map
      map = new_map( $(this) );
  
    });
}