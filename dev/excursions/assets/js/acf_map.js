/* global getApi */
/* global ymaps */
/* global myajax */

const miniMapWidget = (() => {
  const baseColor = '#005281'; // 015a8d
  const activeColor = '#bc3134'; // ffd649

  const $openMapButton = document.querySelector('#OpenMap_btn');
  const $miniMap = document.querySelector('.mini-map');
  const $mapCover = $miniMap ? $miniMap.querySelector('.map-cover') : null;
  const $markerArr = $miniMap ? $miniMap.querySelectorAll('.marker') : null;

  const autoopen = $openMapButton && $openMapButton.classList.contains('autoopen');
  const boundsAll = $miniMap.classList.contains('event-map');
  const bigScreen = window.screen.width > 768;

  function wait(ms) {
    return new Promise((resolve) => {
      if (ms) {
        setTimeout(() => resolve(), ms);
      } else {
        resolve();
      }
    });
  }

  function addMarker(obj, geoObjectCollection) {
    // console.log('addMarker:', obj);
    const lat = obj.lat || null;
    const lng = obj.lng || null;
    // const postId = obj.post_id || null;
    const url = obj.permalink || null;
    const title = obj.title || null;
    const thumbUrl = obj.thumb_url || null;
    const isMarker = obj.marker || false;
    const iconContent = obj.icon_content || '';

    if (lat && lng) {
      // Макеты балуна и хинта (одинаковые)
      let titleStr = title ? '{{ properties.title }}' : null;
      let img = thumbUrl ? '<img src="{{ properties.thumbUrl }}" />' : '';
      if (url) {
        titleStr = titleStr ? `<a href="{{ properties.url }}">${titleStr}</a>` : titleStr;
        img = img ? `<a href="{{ properties.url }}">${img}</a>` : img;
      }
      const template = (titleStr ? `<h3>${titleStr}</h3>` : '') + img;
      const balloonContentLayout = template ? ymaps.templateLayoutFactory.createClass(template) : null;
      const hintContentLayout = template ? ymaps.templateLayoutFactory.createClass(template) : null;

      // Создание геообъекта с типом точка (метка)
      const marker = new ymaps.Placemark(
        [lat, lng],
        {
          url,
          title,
          thumbUrl,
          iconContent,
        },
        {
          preset: 'islands#Icon',
          iconColor: isMarker ? activeColor : baseColor,
          balloonContentLayout,
          hintContentLayout,
        },
      );

      // Размещение геообъекта на карте
      // map.geoObjects.add(marker);
      geoObjectCollection.add(marker);
    }
  }

  function newMap($el, objects) {
    console.log('--- newMap');
    const map = new ymaps.Map($el, {
      center: [52.967631, 36.069584],
      zoom: 12,
      // Подключаем только: Переключатель слоев карты, Полноэкранный режим, Ползунок масштаба, Линейку
      controls: ['typeSelector', 'fullscreenControl', 'zoomControl', 'rulerControl'],
    });

    map.behaviors.disable('scrollZoom');

    // Удалим с карты Геолокацию, Поиск по карте, Пробки
    // map.controls.remove('geolocationControl');
    // map.controls.remove('searchControl');
    // map.controls.remove('trafficControl');

    // objects.forEach(obj => addMarker(obj, map));
    const nomarkers = objects.filter(obj => !obj.marker);
    // console.log('nomarkers', nomarkers);
    nomarkers.forEach(obj => addMarker(obj, map.geoObjects));
    let bounds = map.geoObjects.getBounds();
    // console.log('nomarkers bounds', bounds);

    const markers = objects.filter(obj => obj.marker);
    if (markers.length > 0) {
      // console.log('markers', markers);
      const markersGOC = new ymaps.GeoObjectCollection({}, {});
      markers.forEach(obj => addMarker(obj, markersGOC));
      map.geoObjects.add(markersGOC);
      bounds = boundsAll ? map.geoObjects.getBounds() : markersGOC.getBounds();
      // console.log('markers bounds', bounds);
    }

    // console.log('1', map.getZoom());
    map.setBounds(bounds, {
      checkZoomRange: true,
      duration: 600,
    }).then(() => {
      // console.log('2', map.getZoom());
      if (map.getZoom() < 12) map.setZoom(12);
      if (map.getZoom() > 16) map.setZoom(16);
      // console.log('3', map.getZoom());
    }, err => console.err('setBounds error', err));

    window.addEventListener('resize', () => map.container.fitToViewport());

    return map;
  }

  function makeMiniMap(objects) {
    if ($openMapButton && autoopen && !bigScreen) {
      $openMapButton.classList.add('rotate');
    }

    if (objects) {
      getApi('ymaps')
        .then(() => {
          if ($openMapButton) $openMapButton.style.display = 'none';
          let animDuration = 0;
          if (!autoopen) {
            $miniMap.classList.add('grow');
            animDuration = 700; // .grow dur +
            if ($mapCover) {
              $mapCover.classList.add('fadeout');
              wait(2000).then(() => $mapCover.classList.add('hide'));
            }
          }
          return wait(animDuration);
        })
        .then(() => {
          if (!autoopen) {
            $miniMap.style.height = 'auto';
            // $miniMap.classList.remove('grow');
            // $miniMap.classList.remove('pregrow');
          }
          // Функция ymaps.ready() будет вызвана, когда загрузятся все компоненты API, а также когда будет готово DOM-дерево
          ymaps.ready(() => newMap($miniMap, objects));
        });
    }
  }

  function init() {
    let objects = [];
    if ($markerArr.length) {
      $markerArr.forEach(($marker) => {
        const obj = {
          lat: $marker.getAttribute('data-lat') || null,
          lng: $marker.getAttribute('data-lng') || null,
          post_id: Number($marker.getAttribute('data-post_id')) || null,
          marker: true,
        };
        objects.push(obj);
      });
    }

    const checkSights = new Promise((resolve) => {
      if ($miniMap) {
        const dataSights = $miniMap.getAttribute('data-sights');
        // console.log(dataSights);
        if (dataSights) {
          $miniMap.removeAttribute('data-sights');
          const getSights = new Promise((resolve2) => {
            if (dataSights === 'sights') {
              const searchStr = window.location.search ? `${window.location.search}&` : '?';
              const requestUrl = `${myajax.url + searchStr}action=get_sights`;
              // console.log(requestUrl);
              fetch(requestUrl, { headers: { 'content-type': 'application/x-www-form-urlencoded; charset=UTF-8' } })
                .then(response => resolve2(response.json()));
            } else {
              resolve2(JSON.parse(dataSights));
            }
          });

          getSights
            .then((sights) => {
              // console.log(sights);
              if (sights && sights.length) {
                const objectsIds = objects.map(obj => obj.post_id);
                // console.log(objectsIds);
                const sightsArr = sights.filter(s => objectsIds.indexOf(s.post_id) === -1);
                // console.log(sightsArr);
                // objects.push(...sightsArr);
                objects = sightsArr.concat(objects);
              }
              resolve();
            });
        } else {
          resolve();
        }
      } else {
        resolve();
      }
    });

    checkSights
      .then(() => {
        // console.log(objects);
        if (objects.length) {
          if (autoopen && bigScreen) {
            // console.log('autoopen && screen.width > 768');
            makeMiniMap(objects);
          } else if ($openMapButton) {
            $openMapButton.style.display = 'block';
            $openMapButton.addEventListener('click', () => makeMiniMap(objects), { ones: true });
          }
        }
      });
  }

  return {
    init,
  };
})();

window.addEventListener('load', miniMapWidget.init);
