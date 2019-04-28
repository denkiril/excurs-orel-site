// global
let no_obj_map              = true;
let omb_list_images_loaded  = false;
const baseColor             = '#005281'; // 015a8d
const activeColor           = '#bc3134'; // ffd649
// let objects              = [];
const placeholder_url       = '/wp-content/themes/excursions/assets/img/placeholder_3x2.png';

window.addEventListener('load', init_GB_Map);

function init_GB_Map(){
    const obj_map = document.querySelector('.obj_map');

    if(obj_map){
        const om_content = obj_map.querySelector('.om_content');
        const omb_panel = obj_map.querySelector('.omb_panel');
        if(omb_panel){
            omb_panel.removeAttribute('style'); // style="display: none;"
        }

        const OpenMap_btn = obj_map.querySelector('.OpenMap_btn');
        if(OpenMap_btn){
            OpenMap_btn.addEventListener('click', () => ClickOpenMapBtn(obj_map, OpenMap_btn));
        }

        if( screen.width > 768 ){
            if(OpenMap_btn){
                ClickOpenMapBtn(obj_map, OpenMap_btn);
            }
            else{
                NewObjMap(obj_map);
                no_obj_map = false;
                // om_content.removeAttribute('style');
                NavBlock.classList.remove('fixable');
            }
        }
        else if(om_content){
            om_content.style.display = 'none';
        }
    }
} 

function ClickOpenMapBtn(obj_map, button){
    // if( button.attr('data-state') == 'open' ){
    // if(!button) button = this;
    // console.log(this);

    if( button.dataset.state == 'open' ){
        if( no_obj_map ){
            NewObjMap(obj_map);
            no_obj_map = false;
        }

        button.dataset.state = 'close';
        button.innerHTML ='[Закрыть карту]';
        obj_map.querySelector('.om_content').removeAttribute('style');
        NavBlock.classList.remove('fixable');
    }
    else{
        button.dataset.state = 'open';
        button.innerHTML ='[Открыть карту]';
        obj_map.querySelector('.om_content').style.display = 'none';
        NavBlock.classList.add('fixable');
    }
}

function NewObjMap(objects_map){
    
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

    // objects_map.appendChild(em_content);
    const om_content = objects_map.querySelector('.om_content');
    om_content.insertAdjacentHTML('beforeend', markup);

    
    const fetchInit = { 
        // method: 'POST', 
        // headers: { 'content-type': 'application/x-www-form-urlencoded; charset=UTF-8', 'x-requested-with': 'XMLHttpRequest' },
        headers: { 'content-type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        // body: 'action=get_sights'
    };

    search_str = window.location.search ? window.location.search + '&' : '?';
    request_url = myajax.url + search_str + 'action=get_sights';
    // const searchParams = new URLSearchParams(window.location.search);
    // request_url += '&' + searchParams.toString();

    getScript(ymaps_api_url).then( () => {
        fetch( request_url, fetchInit )
        .then( response => response.json() )
        .then( objects => {
            // objects = json;
            // console.log(objects);
            // Функция ymaps.ready() будет вызвана, когда загрузятся все компоненты API, а также когда будет готово DOM-дерево.
            ymaps.ready( () => { 
                map = NewMap(objects_map, objects);
                // storage = ymaps.geoQuery(map.geoObjects);
                storage = ymaps.geoQuery(map.markers);
            });
            NewObjList(objects_map, objects);
        })
    });

    // objects_map.querySelectorAll('input[type="checkbox"]').forEach( input => {
    //     input.addEventListener( 'change', function() {
    //         console.log('change checkbox '+this.name+', checked='+this.checked);
    //     })
    // });

    objects_map.querySelectorAll('input[name="omb_list_view"]').forEach( input => {
        input.addEventListener( 'change', function() {
            obj_list = objects_map.querySelector('.obj_list');
            // console.log(this.value);
            if(this.value == 'imgs'){
                if( !omb_list_images_loaded ){
                    obj_list.querySelectorAll('img[data-src]').forEach( function(img){
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    });
                    omb_list_images_loaded = true;
                }
                obj_list.classList.add('list_images');                
            } 
            else{
                obj_list.classList.remove('list_images');
            }

        })
    });

    // objects_map.querySelector('#filterByTitle').addEventListener( 'keyup', filterByTitle );
    objects_map.querySelector('#filterByTitle').addEventListener( 'input', filterByTitle );

}

function NewMap(objects_map, objects) {

    // const evMap = $('<div class="ev-map"></div>');
    const emb_map = objects_map.querySelector('.omb_map');
    const evMap = document.createElement('div');
    evMap.className = "ev-map";
    emb_map.appendChild(evMap);

    const map = new ymaps.Map( evMap, { // evMap[0]
        center: [52.967631, 36.069584], 
        zoom: 12
    });
    
    if(screen.width <= 768){
        map.behaviors.disable('scrollZoom');
    }
    
    map.markers = [];

    // const ev_length = objects.length;
    // for(i = 0; i < ev_length; i++){ add_marker( objects[i], map ); }
    objects.forEach( obj => add_marker(obj, map));

    // Кластеризация и размещение геообъектов на карте.
    // map.geoObjects.add(marker);
    clusterer = new ymaps.Clusterer({ 
        clusterIconColor: baseColor 
    });
    clusterer.add(map.markers);
    map.geoObjects.add(clusterer);

    // center_map( map );
    if(objects.length > 0){
        map.setBounds(
            map.geoObjects.getBounds(), 
            { checkZoomRange: true }
            ).then(function(){ 
                if(map.getZoom() > 16) map.setZoom(16); 
            });
    }

    return map;
}

function add_marker(obj, map) {

    const lat       = obj.lat;
    const lng       = obj.lng;
    const post_id   = obj.post_id;
    const url       = obj.permalink;
    const title     = obj.title;
    const thumb_url = obj.thumb_url;

    // Макеты балуна и хинта
    let blc_template = '<h3><a href="{{ properties.url }}">{{ properties.title }}</a></h3>';
    if(thumb_url) blc_template += '<img src="{{ properties.thumb_url }}" />';
    const BalloonLayoutClass = ymaps.templateLayoutFactory.createClass( blc_template );

    let hlc_template = '<p>{{ properties.title }}</p>';
    if(thumb_url) hlc_template += '<img src="{{ properties.thumb_url }}" />';
    const HintLayoutClass = ymaps.templateLayoutFactory.createClass( hlc_template );

    const clusterImg = thumb_url ? '<img src="'+thumb_url+'" />' : '';
    // const clusterImg = '<img src="'+thumb_url+'" />';

    // Создание геообъекта с типом точка (метка)
    const marker = new ymaps.Placemark(
        [lat, lng],
        {
            post_id:        post_id,
            url:            url,
            title:          title,
            thumb_url:      thumb_url,
            clusterCaption: title,
            balloonContent: clusterImg+'<p><a href="'+url+'">Перейти на страницу объекта >></a></p>'
        },
        {
            // preset: 'islands#darkBlueIcon',
            preset:                 'islands#Icon',
            iconColor:              baseColor, 
            balloonContentLayout:   BalloonLayoutClass,
            hintContentLayout:      HintLayoutClass
        }
    );

    // События геообъекта
    marker.events.add('mouseenter', function(e) {
        const mark = e.get('target');
        mark.options.set('iconColor', activeColor); 
    });

    marker.events.add('mouseleave', function(e) {
        const mark = e.get('target');
        mark.options.set('iconColor', baseColor);
    });

    marker.events.add('balloonopen', function(e) {
        const mark = e.get('target');
        mark.options.set('iconColor', activeColor); 
    });

    marker.events.add('balloonclose', function(e) {
        const mark = e.get('target');
        mark.options.set('iconColor', baseColor);
    });

    // Добавляем новый геообъект в массив
    map.markers.push(marker);

    // Размещение геообъекта на карте.
    // map.geoObjects.add(marker);

}

function NewObjList( objects_map, objects ){
    const emb_list = objects_map.querySelector('.omb_list');
    const obj_list = document.createElement('ul');
    obj_list.className = 'obj_list';
    obj_list.setAttribute('tabindex', 0);

    objects.forEach( function(object){

        const thumb_url     = object.thumb_url;
        const nothumb_class = thumb_url ? ''        : 'class="no_thumb"';
        const img_url       = thumb_url ? thumb_url : placeholder_url;

        const markup = `
            <li ${nothumb_class} data-post_id="${object.post_id}" tabindex="-1">
                <span class="li_title">${object.title}</span>
                <span><a class="hiddenlink" href="${object.permalink}" title="Ссылка на ${object.title}">[>>]</a></span>
                <img data-src="${img_url}" />
            </li>`;

        // events_list.appendChild(events_li);
        obj_list.insertAdjacentHTML('beforeend', markup);
    });

    emb_list.appendChild(obj_list);

    obj_list.addEventListener('keydown', function(e){
        // console.log('obj_list keydown');
        switch(e.key) { 
            case 'ArrowDown':
            case 'Enter':
                e.preventDefault();
                const item = obj_list.querySelector('li');
                if(item){
                    item.setAttribute('tabindex', 0);
                    item.focus();
                }
                break;
        }
    });

    // events_list.querySelectorAll('[data-post_id]')
    obj_list.querySelectorAll('li').forEach( function(item){
        // AddListenersToItem(item);
        item.addEventListener('click', function(e){
            // console.log('click');
            // console.log(this);
            // console.log(e.target);
            // e.preventDefault();
            // e.stopImmediatePropagation();
            if(!e.target.matches('a')){
                if(this.classList.contains('checkedItem')){
                    checkItemOff(this);
                }
                else{
                    checkItemOn(this, obj_list, objects);
                }
            }
        });

        if(screen.width > 768){
            item.addEventListener('mouseenter', function(){
                // console.log('mouseenter');
                if(!this.classList.contains('checkedItem')){
                    checkItemOn(this, obj_list, objects);
                }
            });

            item.addEventListener('keydown', function(e){
                // console.log('item keydown');
                let an_item = this;
                let focus = false;

                switch(e.key) { 
                    case 'ArrowUp':
                    // case 'ArrowLeft':
                        do(an_item = an_item.previousElementSibling)
                        while(an_item != null && an_item.style.display == 'none')
                        if(!an_item){
                            an_item = obj_list;
                        } 
                        focus = true;
                        break;

                    case 'ArrowDown':
                    // case 'ArrowRight':
                        do(an_item = an_item.nextElementSibling)
                        while(an_item != null && an_item.style.display == 'none')
                        if(!an_item){
                            an_item = obj_list.firstElementChild;
                        }
                        focus = true;
                        break;

                    case 'Enter':
                        an_item = obj_list;
                        focus = true;
                        break;
                }

                if(focus){
                    an_item.focus();
                    an_item.setAttribute('tabindex', 0);
                    item.setAttribute('tabindex', -1);
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
            // obj_list.addEventListener('mouseout', function(e){
            //     if(e.target.matches('[data-post_id]')) checkItemOff(e.target);
            // });
            item.addEventListener('focus', function(){
                // console.log('focus');
                if(!this.classList.contains('checkedItem')){
                    checkItemOn(this, obj_list, objects);
                }
            });
        }
    });
}

function checkItemOn(item, list, objects){
    // if(item.classList.contains('checkedItem')) return;
    list.querySelectorAll('.checkedItem').forEach( chItem => checkItemOff(chItem) );
    item.classList.add('checkedItem');

    const post_id = item.dataset.post_id;
    // Выборка геообъектов
    storage.search('properties.post_id = ' + post_id).each( function(mark){
        const geoObjectState = clusterer.getObjectState(mark);
        // Проверяем, находится ли объект в видимой области карты.
        if(geoObjectState.isShown){
            // Если объект попадает в кластер, перекрашиваем значок кластера
            if(geoObjectState.isClustered){
                geoObjectState.cluster.options.set('clusterIconColor', activeColor);
            } else {
                // Если объект не попал в кластер, перекрашиваем значок объекта
                mark.options.set('iconColor', activeColor);
            }
        }
    });
    // infoImg
    const object  = objects.find( el => el.post_id == post_id );
    const img_url = object.thumb_url ? object.thumb_url : placeholder_url;

    document.querySelector('#infoImg').src          = img_url;          //item.querySelector('img').src;
    document.querySelector('#infoRef').href         = object.permalink; //item.querySelector('a').href;
    document.querySelector('#infoRef').textContent  = object.title;
}

function checkItemOff(item, uncheck=true){
    const post_id = item.dataset.post_id;
    // Выборка геообъектов
    storage.search('properties.post_id = ' + post_id).each( function(mark){
        const geoObjectState = clusterer.getObjectState(mark);
        if(geoObjectState.isShown){
            if(geoObjectState.isClustered){
                geoObjectState.cluster.options.set('clusterIconColor', baseColor);
            } else {
                // Если объект не попал в кластер
                mark.options.set('iconColor', baseColor);
            }
        }
    });

    if(uncheck){
        item.classList.remove('checkedItem');
        document.querySelector('#infoImg').src          = "";
        document.querySelector('#infoRef').href         = "";
        document.querySelector('#infoRef').textContent  = "";
    }
}

function filterByTitle(){
    filter = this.value.toUpperCase();
    // console.log('filter='+filter);
    obj_list = document.querySelector('.obj_list');
    obj_list.querySelectorAll('li').forEach( function(item){
        const title = item.querySelector('.li_title');
        titleTxt = title.textContent || title.innerText;
        // console.log(titleTxt);
        if(titleTxt.toUpperCase().includes(filter)){
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}
