// import '../css/events_map.css';

/* global NavBlock */
/* global getApi */
/* global myajax */
/* global ymaps */

const baseColor = '#005281'; // 015a8d
const activeColor = '#bc3134'; // ffd649

function addMarker(event, map) {
  const { lat } = event;
  const { lng } = event;
  const postId = event.post_id;
  const url = event.permalink;
  const { title } = event;

  // Макеты балуна и хинта
  const EventBalloonLayoutClass = ymaps.templateLayoutFactory.createClass(
    '<h3><a href="{{ properties.url }}">{{ properties.title }}</a></h3>',
  );

  const EventHintLayoutClass = ymaps.templateLayoutFactory.createClass(
    '<p>{{ properties.title }}</p>',
    // + '<p>{{ properties.postId }}</p>'
  );

  // Создание геообъекта с типом точка (метка)
  const marker = new ymaps.Placemark(
    [lat, lng],
    {
      postId,
      url,
      title,
    },
    {
      // preset: 'islands#darkBlueIcon',
      preset: 'islands#Icon',
      iconColor: baseColor,
      balloonContentLayout: EventBalloonLayoutClass,
      hintContentLayout: EventHintLayoutClass,
    },
  );

  // События геообъекта
  marker.events.add('mouseenter', (e) => {
    const mark = e.get('target');
    mark.options.set('iconColor', activeColor);
  });

  marker.events.add('mouseleave', (e) => {
    const mark = e.get('target');
    mark.options.set('iconColor', baseColor);
  });

  marker.events.add('balloonopen', (e) => {
    const mark = e.get('target');
    mark.options.set('iconColor', activeColor);
  });

  marker.events.add('balloonclose', (e) => {
    const mark = e.get('target');
    mark.options.set('iconColor', baseColor);
  });

  // Добавляем новый геообъект в массив
  map.markers.push(marker);

  // Размещение геообъекта на карте.
  // map.geoObjects.add(marker);
}

function NewMap(embMap, events, clusterer) {
  const evMap = document.createElement('div');
  evMap.className = 'ev-map';
  embMap.appendChild(evMap);

  const map = new ymaps.Map(evMap, { // evMap[0]
    center: [52.967631, 36.069584],
    zoom: 12,
  });

  if (window.screen.width <= 768) {
    map.behaviors.disable('scrollZoom');
  }

  map.markers = [];

  const evLength = events.length;
  // eslint-disable-next-line no-plusplus
  for (let i = 0; i < evLength; i++) {
    addMarker(events[i], map);
  }

  // Кластеризация и размещение геообъектов на карте.
  // map.geoObjects.add(marker);
  clusterer.add(map.markers);
  map.geoObjects.add(clusterer);

  // center_map( map );
  if (evLength > 0) {
    map.setBounds(
      map.geoObjects.getBounds(),
      { checkZoomRange: true },
    ).then(() => {
      if (map.getZoom() > 16) map.setZoom(16);
    });
  }

  return map;
}

function AddListenersToItem(item, storage, clusterer) {
  item.addEventListener('mouseenter', (e) => {
    // if (e.target.matches('[data-post_id]')) {
    const postId = e.target.getAttribute('data-post_id');
    if (postId) {
      // const postId = e.target.dataset.post_id;
      // const postId = e.target.getAttribute('data-post_id');
      // Выборка геообъектов
      storage.search(`properties.postId = ${postId}`).each((mark) => {
        const geoObjectState = clusterer.getObjectState(mark);
        // Проверяем, находится ли объект в видимой области карты.
        if (geoObjectState.isShown) {
          // Если объект попадает в кластер, перекрашиваем значок кластера
          if (geoObjectState.isClustered) {
            geoObjectState.cluster.options.set('clusterIconColor', activeColor);
          } else {
            // Если объект не попал в кластер, перекрашиваем значок объекта
            mark.options.set('iconColor', activeColor);
          }
        }
      });
    }
  });

  // events_li.mouseleave( function(e){
  item.addEventListener('mouseleave', (e) => {
    // if(e.target.matches('.events_li')) {
    // if (e.target.matches('[data-post_id]')) {
    const postId = e.target.getAttribute('data-post_id');
    if (postId) {
      // const postId = e.target.dataset.post_id;
      storage.search(`properties.postId = ${postId}`).each((mark) => {
        const geoObjectState = clusterer.getObjectState(mark);
        if (geoObjectState.isShown) {
          if (geoObjectState.isClustered) {
            geoObjectState.cluster.options.set('clusterIconColor', baseColor);
          } else {
            // Если объект не попал в кластер
            mark.options.set('iconColor', baseColor);
          }
        }
      });
    }
  });
}

function NewEventsList(embList, events, storage, clusterer) {
  // const events_list = $('<ul class="events_list"></ul>');
  const eventsList = document.createElement('ul');
  eventsList.className = 'events_list';

  events.forEach((event) => {
    const hiddenlink = `<a class="hiddenlink" href="${event.permalink}">[Перейти\u00A0>>]</a>`;

    // <li class="events_li" data-post_id="${event.post_id}">${event.title}</li>
    const eventsLi = document.createElement('li');
    eventsLi.className = 'events_li';
    eventsLi.setAttribute('data-post_id', event.post_id);
    // eventsLi.dataset.post_id = event.post_id;
    eventsLi.innerHTML = event.title;
    eventsLi.insertAdjacentHTML('beforeend', hiddenlink);
    AddListenersToItem(eventsLi, storage, clusterer);

    eventsList.appendChild(eventsLi);
  });

  // embList.append(events_list);
  embList.appendChild(eventsList);
}

function NewEventsMap(eventsMap) {
  const emContent = document.createElement('div');
  emContent.className = 'events_map_content';
  const embMap = document.createElement('div');
  embMap.className = 'events_block events_block_map';
  const embFilter = document.createElement('div');
  embFilter.className = 'events_block events_block_filter';
  const embList = document.createElement('div');
  embList.className = 'events_block events_block_list';

  emContent.appendChild(embMap);
  emContent.appendChild(embFilter);
  emContent.appendChild(embList);
  eventsMap.appendChild(emContent);

  const fetchInit = {
    method: 'POST',
    // headers: { 'content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
    // 'x-requested-with': 'XMLHttpRequest' },
    headers: { 'content-type': 'application/x-www-form-urlencoded; charset=UTF-8' },
    body: 'action=get_events',
  };

  getApi('ymaps').then(() => {
    fetch(myajax.url, fetchInit)
      .then(response => response.json())
      .then((events) => {
        // console.log(events);
        // Функция ymaps.ready() будет вызвана, когда загрузятся все компоненты API,
        // а также когда будет готово DOM-дерево.
        ymaps.ready(() => {
          const clusterer = new ymaps.Clusterer({
            clusterIconColor: baseColor,
          });
          const map = NewMap(embMap, events, clusterer);
          // storage = ymaps.geoQuery(map.geoObjects);
          const storage = ymaps.geoQuery(map.markers);
          NewEventsList(embList, events, storage, clusterer);
        });
      });
  });
}

function ClickEventsMapBtn(map) {
  console.log('ClickEventsMapBtn');
  // switch (map.dataset.state) {
  switch (map.getAttribute('data-state')) {
    case 'init':
      console.log('init');
      NewEventsMap(map);
      map.setAttribute('data-state', 'open');
      break;

    case 'close':
      map.setAttribute('data-state', 'open');
      break;

    case 'open':
      map.setAttribute('data-state', 'close');
      break;

    default: break;
  }

  // if (map.dataset.state === 'open') {
  if (map.getAttribute('data-state') === 'open') {
    NavBlock.classList.remove('fixable');
  } else {
    NavBlock.classList.add('fixable');
  }
}

[].forEach.call(document.querySelectorAll('.events_map'), (eventsMap) => {
  eventsMap.setAttribute('style', 'display: block;');
  const button = eventsMap.querySelector('.NewEventsMap_btn');

  button.addEventListener('click', () => {
    ClickEventsMapBtn(eventsMap);
  });
});
