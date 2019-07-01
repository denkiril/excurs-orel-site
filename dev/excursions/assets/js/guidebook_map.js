import '../css/guidebook_map.css';

// const URLSearchParams = require('url-search-params');

let ombListImagesLoaded = false;
const baseColor = '#005281'; // 015a8d
const activeColor = '#bc3134'; // ffd649
// let objects              = [];
const placeholderUrl = '/wp-content/themes/excursions/assets/img/placeholder_3x2.png';
/* global NavBlock */
/* global getScript */
/* global ymapsApiUrl */
/* global myajax */
/* global ymaps */


function addMarker(obj, map) {
  const { lat } = obj;
  const { lng } = obj;
  const postId = obj.post_id;
  const url = obj.permalink;
  const { title } = obj;
  const thumbUrl = obj.thumb_url;

  // Макеты балуна и хинта
  let blcTemplate = '<h3><a href="{{ properties.url }}">{{ properties.title }}</a></h3>';
  if (thumbUrl) blcTemplate += '<img src="{{ properties.thumbUrl }}" />';
  const BalloonLayoutClass = ymaps.templateLayoutFactory.createClass(blcTemplate);

  let hlcTemplate = '<p>{{ properties.title }}</p>';
  if (thumbUrl) hlcTemplate += '<img src="{{ properties.thumbUrl }}" />';
  const HintLayoutClass = ymaps.templateLayoutFactory.createClass(hlcTemplate);

  const clusterImg = thumbUrl ? `<img src="${thumbUrl}" />` : '';
  // const clusterImg = '<img src="'+thumbUrl+'" />';

  // Создание геообъекта с типом точка (метка)
  const marker = new ymaps.Placemark(
    [lat, lng],
    {
      postId,
      url,
      title,
      thumbUrl,
      clusterCaption: title,
      balloonContent: `${clusterImg}<p><a href="${url}">Перейти на страницу объекта >></a></p>`,
    },
    {
      // preset: 'islands#darkBlueIcon',
      preset: 'islands#Icon',
      iconColor: baseColor,
      balloonContentLayout: BalloonLayoutClass,
      hintContentLayout: HintLayoutClass,
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

function NewMap(objectsMap, objects, clusterer) {
  // const evMap = $('<div class="ev-map"></div>');
  const embMap = objectsMap.querySelector('.omb_map');
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

  // const ev_length = objects.length;
  // for(i = 0; i < ev_length; i++){ addMarker( objects[i], map ); }
  objects.forEach(obj => addMarker(obj, map));

  // Кластеризация и размещение геообъектов на карте.
  // map.geoObjects.add(marker);
  clusterer.add(map.markers);
  map.geoObjects.add(clusterer);

  // center_map( map );
  if (objects.length > 0) {
    map.setBounds(
      map.geoObjects.getBounds(),
      { checkZoomRange: true },
    ).then(() => {
      if (map.getZoom() > 16) map.setZoom(16);
    });
  }

  return map;
}

function filterByTitle(value, objList) {
  const filter = value.toUpperCase();
  // console.log('filter='+filter);
  // const objList = document.querySelector('.objList');
  if (objList) {
    // objList.querySelectorAll('li').forEach( function(item){
    [].forEach.call(objList.querySelectorAll('li'), (item) => {
      const title = item.querySelector('.li_title');
      const titleTxt = title.textContent || title.innerText;
      // console.log(titleTxt);
      if (titleTxt.toUpperCase().includes(filter)) {
        // item.style.display = '';
        item.removeAttribute('style');
      } else {
        // item.style.display = 'none';
        item.setAttribute('style', 'display: none;');
      }
    });
  }
}

function checkItemOff(item, storage, clusterer, uncheck = true) {
  // const postId = item.dataset.post_id;
  const postId = item.getAttribute('data-post_id');
  // Выборка геообъектов
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

  if (uncheck) {
    item.classList.remove('checkedItem');
    document.querySelector('#infoImg').src = '';
    document.querySelector('#infoRef').href = '';
    document.querySelector('#infoRef').textContent = '';
  }
}

function checkItemOn(item, list, objects, storage, clusterer) {
  // if(item.classList.contains('checkedItem')) return;
  // list.querySelectorAll('.checkedItem').forEach( chItem => checkItemOff(chItem) );
  [].forEach.call(list.querySelectorAll('.checkedItem'), chItem => checkItemOff(chItem, storage, clusterer));
  item.classList.add('checkedItem');

  // const postId = item.dataset.post_id;
  const postId = item.getAttribute('data-post_id');
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
  // infoImg
  // eslint-disable-next-line eqeqeq
  const object = objects.find(el => el.post_id == postId);
  const imgUrl = object.thumb_url ? object.thumb_url : placeholderUrl;

  document.querySelector('#infoImg').src = imgUrl; // item.querySelector('img').src;
  document.querySelector('#infoRef').href = object.permalink; // item.querySelector('a').href;
  document.querySelector('#infoRef').textContent = object.title;
}

function NewObjList(objectsMap, objects, storage, clusterer) {
  const embList = objectsMap.querySelector('.omb_list');
  const objList = document.createElement('ul');
  objList.className = 'obj_list';
  objList.setAttribute('tabindex', 0);

  objects.forEach((object) => {
    const thumbUrl = object.thumb_url;
    const nothumbClass = thumbUrl ? '' : 'class="no_thumb"';
    const imgUrl = thumbUrl || placeholderUrl;

    const markup = `
            <li ${nothumbClass} data-post_id="${object.post_id}" tabindex="-1">
                <span class="li_title">${object.title}</span>
                <a class="hiddenlink" href="${object.permalink}" title="Ссылка на ${object.title}">[>>]</a>
                <img data-src="${imgUrl}" />
            </li>`;

    // events_list.appendChild(events_li);
    objList.insertAdjacentHTML('beforeend', markup);
  });

  embList.appendChild(objList);

  objList.addEventListener('keydown', (e) => {
    console.log('obj_list keydown');
    let item;
    switch (e.key) {
      case 'ArrowDown':
      case 'Enter':
        e.preventDefault();
        item = objList.querySelector('li');
        if (item.style.display === 'none') {
          do (item = item.nextElementSibling);
          while (item != null && item.style.display === 'none');
        }
        if (item) {
          item.setAttribute('tabindex', 0);
          item.focus();
        }
        break;

      default: break;
    }
  });

  // objectsMap.querySelectorAll('input[name="omb_list_view"]').forEach( input => {
  [].forEach.call(objectsMap.querySelectorAll('input[name="omb_list_view"]'), (input) => {
    input.addEventListener('change', function inputListViewChange() {
      // console.log(this.value);
      if (this.value === 'imgs') {
        if (!ombListImagesLoaded) {
          // objList.querySelectorAll('img[data-src]').forEach( function(img){
          [].forEach.call(objList.querySelectorAll('img[data-src]'), (img) => {
            // img.src = img.dataset.src;
            img.setAttribute('src', img.getAttribute('data-src'));
            img.removeAttribute('data-src');
          });
          ombListImagesLoaded = true;
        }
        objList.classList.add('list_images');
      } else {
        objList.classList.remove('list_images');
      }
    });
  });

  // objectsMap.querySelector('#filterByTitle').addEventListener( 'keyup', filterByTitle );
  objectsMap.querySelector('#filterByTitle').addEventListener('input', function filterByTitleInput() {
    filterByTitle(this.value, objList);
  });

  // events_list.querySelectorAll('[data-post_id]')
  // objList.querySelectorAll('li').forEach( function(item){
  [].forEach.call(objList.querySelectorAll('li'), (item) => {
    // AddListenersToItem(item);
    item.addEventListener('click', function objListItemClick(e) {
      // console.log('click');
      // console.log(this);
      // console.log(e.target);
      // e.preventDefault();
      // e.stopImmediatePropagation();
      if (!e.target.matches('a')) {
        if (this.classList.contains('checkedItem')) {
          checkItemOff(this, storage, clusterer);
        } else {
          checkItemOn(this, objList, objects, storage, clusterer);
        }
      }
    });

    if (window.screen.width > 768) {
      item.addEventListener('mouseenter', function objListItemMouseenter() {
        // console.log('mouseenter');
        if (!this.classList.contains('checkedItem')) {
          checkItemOn(this, objList, objects, storage, clusterer);
        }
      });

      item.addEventListener('keydown', function objListItemKeydown(e) {
        // console.log('item keydown');
        let anItem = this;
        let focus = false;

        switch (e.key) {
          case 'ArrowUp':
            // case 'ArrowLeft':
            do (anItem = anItem.previousElementSibling);
            while (anItem != null && anItem.style.display === 'none');
            if (!anItem) {
              anItem = objList;
            }
            focus = true;
            break;

          case 'ArrowDown':
            // case 'ArrowRight':
            do (anItem = anItem.nextElementSibling);
            while (anItem != null && anItem.style.display === 'none');
            if (!anItem) {
              anItem = objList.firstElementChild;
            }
            focus = true;
            break;

          case 'Enter':
            // anItem = objList;
            if (anItem.matches('li')) {
              anItem = anItem.querySelector('a');
              if (anItem) {
                anItem.focus();
              }
              e.stopPropagation();
            } else if (anItem.matches('a')) {
              // document.location.href = anItem.href;
              e.stopPropagation();
            }
            // focus = true;
            break;

          default: break;
        }

        if (focus) {
          anItem.focus();
          anItem.setAttribute('tabindex', 0);
          item.setAttribute('tabindex', -1);
          e.preventDefault();
          e.stopPropagation();
        }
      });
      // objList.addEventListener('mouseout', function(e){
      //     if(e.target.matches('[data-post_id]')) checkItemOff(e.target);
      // });
      item.addEventListener('focus', function objListItemFocus() {
        // console.log('focus');
        if (!this.classList.contains('checkedItem')) {
          checkItemOn(this, objList, objects, storage, clusterer);
        }
      });
    }
  });
}

function NewObjMap(objectsMap) {
  const markup = `
        <div class="om_flex">
            <div class="om_block omb_map"></div>
            <div class="om_aside">
                <div class="om_block omb_filter">
                    <input type="search" placeholder="Поиск по названию" id="filterByTitle">
                    <div class="ctrl_flex">
                        <label title="Переключение вида">
                            <input class="hidden_input" type="radio" name="omb_list_view" value="list" checked>
                            <span class="chb_svg"></span>
                        </label>
                        <label title="Переключение вида">
                            <input class="hidden_input" type="radio" name="omb_list_view" value="imgs">
                            <span class="chb_svg"></span>
                        </label>
                    </div>
                </div>
                <div class="om_block omb_list"></div>
                <div class="om_block omb_info">
                    <img id="infoImg" src="" />
                    <a id="infoRef" href=""></a>
                </div>
            </div>
        </div>`;

  // <label>
  //     <input class="hidden_input" type="checkbox" name="checkbox" id="chb_imgOn">
  //     <span class="chb_svg"></span>
  // </label>

  // objectsMap.appendChild(em_content);
  const omContent = objectsMap.querySelector('.om_content');
  omContent.insertAdjacentHTML('beforeend', markup);

  const fetchInit = {
    // method: 'POST',
    // headers: { 'content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
    // 'x-requested-with': 'XMLHttpRequest' },
    headers: { 'content-type': 'application/x-www-form-urlencoded; charset=UTF-8' },
    // body: 'action=get_sights'
  };

  const searchStr = window.location.search ? `${window.location.search}&` : '?';
  let requestUrl = `${myajax.url + searchStr}action=get_sights`;
  const slug = objectsMap.getAttribute('data-slug');
  if (slug) {
    requestUrl += `&slug=${slug}`;
  }
  // const searchParams = new URLSearchParams(window.location.search);
  // requestUrl += '&' + searchParams.toString();

  getScript(ymapsApiUrl).then(() => {
    fetch(requestUrl, fetchInit)
      .then(response => response.json())
      .then((objects) => {
        // objects = json;
        // console.log(objects);
        // Функция ymaps.ready() будет вызвана, когда загрузятся все компоненты API,
        // а также когда будет готово DOM-дерево.
        ymaps.ready(() => {
          const clusterer = new ymaps.Clusterer({
            clusterIconColor: baseColor,
          });
          const map = NewMap(objectsMap, objects, clusterer);
          // storage = ymaps.geoQuery(map.geoObjects);
          const storage = ymaps.geoQuery(map.markers);
          NewObjList(objectsMap, objects, storage, clusterer);
        });
      });
  });

  // objectsMap.querySelectorAll('input[type="checkbox"]').forEach( input => {
  //     input.addEventListener( 'change', function() {
  //         console.log('change checkbox '+this.name+', checked='+this.checked);
  //     })
  // });
}

function changeObjMapState(objMap) {
  // if( button.attr('data-state') == 'open' ){
  // if(!button) button = this;
  // console.log(this);

  // switch (objMap.dataset.state) {
  switch (objMap.getAttribute('data-state')) {
    case 'init':
      NewObjMap(objMap);
      objMap.setAttribute('data-state', 'open');
      break;

    case 'close':
      // button.dataset.state = 'open';
      objMap.setAttribute('data-state', 'open');
      // button.innerHTML = '[Открыть карту]';
      // objMap.querySelector('.omContent').style.display = 'none';
      // objMap.querySelector('.omContent').setAttribute('style', 'display: none;');
      break;

    case 'open':
      // button.dataset.state = 'close';
      objMap.setAttribute('data-state', 'close');
      // button.innerHTML = '[Закрыть карту]';
      // objMap.querySelector('.omContent').removeAttribute('style');
      break;

    default: break;
  }

  // if (objMap.dataset.state === 'open') {
  if (objMap.getAttribute('data-state') === 'open') {
    NavBlock.classList.remove('fixable');
  } else {
    NavBlock.classList.add('fixable');
  }
}

function initGBMap() {
  const objMap = document.querySelector('.obj_map');

  if (objMap) {
    // const omContent = objMap.querySelector('.om_content');
    const ombPanel = objMap.querySelector('.omb_panel');
    if (ombPanel) {
      ombPanel.removeAttribute('style'); // style="display: none;"
    }

    const OpenMapBtn = objMap.querySelector('.OpenMap_btn');
    if (OpenMapBtn) {
      OpenMapBtn.addEventListener('click', () => changeObjMapState(objMap));
    }

    if (window.screen.width > 768) {
      const searchStr = window.location.search;
      // const searchParams = new URLSearchParams(window.location.search);
      // if (!searchParams.has('pagenum')) {
      if (!(searchStr.includes('pagenum=') || objMap.classList.contains('noautoopen'))) {
        changeObjMapState(objMap);
      }
    }
    // else if (omContent) {
    //   omContent.style.display = 'none';
    // }
  }
}

window.addEventListener('load', initGBMap);
