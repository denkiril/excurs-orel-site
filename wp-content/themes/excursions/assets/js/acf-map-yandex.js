// Функция ymaps.ready() будет вызвана, когда
// загрузятся все компоненты API, а также когда будет готово DOM-дерево.
ymaps.ready(init);

function init(){ 

  function new_map( $el ) {

      var $markers = $el.find('.marker');
      
      // var args = {
      //   zoom: 16,
      //   center: [0, 0]
      // }; 
      // var map = new google.maps.Map( $el[0], args);

      var map = new ymaps.Map( $el[0], {
        center: [0, 0],
        zoom: 16
      });
      
      map.behaviors.disable('scrollZoom');

      map.markers = [];
      
      $markers.each(function(){
          add_marker( $(this), map );
      });

      // center_map( map );
      map.setBounds(map.geoObjects.getBounds(), 
        {checkZoomRange:true}).then(function(){ if(map.getZoom() > 16) map.setZoom(16); });

      return map;
    }

    function add_marker( $marker, map ) {
  
      // var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
      var lat = $marker.attr('data-lat');
      var lng = $marker.attr('data-lng');
    
      // create marker
      // var marker = new google.maps.Marker({
      //   position	: latlng,
      //   map			: map
      // });

      // Создание геообъекта с типом точка (метка)
      var marker = new ymaps.Placemark([lat, lng]);

      // Размещение геообъекта на карте.
      map.geoObjects.add(marker);

      // add to array
      map.markers.push(marker);
    
      // // if marker contains HTML, add it to an infoWindow
      // if( $marker.html() )
      // {
      //   // create info window
      //   var infowindow = new google.maps.InfoWindow({
      //     content		: $marker.html()
      //   });
    
      //   // show info window when marker is clicked
      //   google.maps.event.addListener(marker, 'click', function() {
    
      //     infowindow.open( map, marker );
    
      //   });
      // }
    }

    // function center_map( map ) {
  
      // var bounds = map.geoObjects.getBounds();
      // map.setBounds(map.geoObjects.getBounds(), {checkZoomRange:true}).then(function(){ if(map.getZoom() > 16) map.setZoom(16); });
    
      // only 1 marker?
      // if( map.markers.length == 1 )
      // {
      //   // set center of map
      //   map.setCenter( bounds.getCenter() );
      //   map.setZoom( 16 );
      // }
      // else
      // {
      //   // fit to bounds
      //   map.fitBounds( bounds );
      // }
    // }

    // Создание карты.    
    // var myMap = new ymaps.Map("acf-map", {
        // Координаты центра карты.
        // Порядок по умолчанию: «широта, долгота».
        // Чтобы не определять координаты центра карты вручную,
        // воспользуйтесь инструментом Определение координат.
        // center: [52.97828740352413, 36.109330110334895],
        // Уровень масштабирования. Допустимые значения:
        // от 0 (весь мир) до 19.
        // zoom: 16
    // });

    // Создание геообъекта с типом точка (метка)
    // var myPlacemark = new ymaps.Placemark([52.97828740352413, 36.109330110334895]);

    // Размещение геообъекта на карте.
    // myMap.geoObjects.add(myPlacemark);

    $('.acf-map').each(function(){
  
      // create map
      map = new_map( $(this) );
  
    });
}