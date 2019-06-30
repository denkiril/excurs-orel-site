/* global getScript */
/* global ymapsApiUrl */
/* global ymaps */
const OpenMapButton = document.querySelector('#OpenMap_btn');


function acfMapInit() {
  function addMarker($marker, map) {
    // const { lat } = $marker.dataset;
    // const { lng } = $marker.dataset;
    const lat = $marker.getAttribute('data-lat');
    const lng = $marker.getAttribute('data-lng');

    // Создание геообъекта с типом точка (метка)
    const marker = new ymaps.Placemark(
      [lat, lng],
      {},
      {
        preset: 'islands#Icon',
        iconColor: '#005281', // 015a8d
      },
    );

    // Размещение геообъекта на карте.
    map.geoObjects.add(marker);

    // add to array
    map.markers.push(marker);
  }

  function newMap($el) {
    // var $markers = $el.find('.marker');
    // var $markers = $el.querySelectorAll('.marker');

    const map = new ymaps.Map($el, { // var ... $el[0]
      center: [0, 0],
      zoom: 16,
    });

    map.behaviors.disable('scrollZoom');

    map.markers = [];

    // $markers.each(function(){ addMarker( $(this), map ); });
    [].forEach.call($el.querySelectorAll('.marker'), ($marker) => {
      addMarker($marker, map);
    });

    // center_map( map );
    // console.log(map.getZoom()+'\n');
    map.setBounds(
      map.geoObjects.getBounds(),
      { checkZoomRange: true },
    ).then(() => {
      const zoom = map.getZoom();
      if (zoom < 12 || zoom > 16) map.setZoom(16);
    });

    return map;
  }

  // $('.acf-map').each(function(){ map = newMap( $(this) ); });
  [].forEach.call(document.querySelectorAll('.acf-map'), (elem) => {
    newMap(elem);
    // elem.style.display = 'block';
    elem.setAttribute('style', 'display: block;');
  });
}

function OpenEventMap() {
  OpenMapButton.style.display = 'none';

  getScript(ymapsApiUrl).then(() => {
    // Функция ymaps.ready() будет вызвана, когда загрузятся все компоненты API,
    // а также когда будет готово DOM-дерево.
    ymaps.ready(acfMapInit);
  });
}

function initOpenEventMap() {
  if (window.screen.width > 768) {
    // console.log('screen.width > 768');

    // acf-map only for big screens
    OpenEventMap();
  } else if (OpenMapButton) {
    OpenMapButton.style.display = 'block';
    // $('#OpenMap_btn').click(function() { OpenEventMap(); })
    OpenMapButton.addEventListener('click', OpenEventMap);
  }
}

window.addEventListener('load', initOpenEventMap);
