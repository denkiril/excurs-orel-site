/*
    script.js: 
    $('.events_map').each(function() > ClickEventsMapBtn(events_map, button) > NewEventsMap(events_map);
*/
// global
var no_events_map = true;
const baseColor = '#005281'; // 015a8d
const activeColor = '#bc3134'; // ffd649

// events_map_sel.forEach( function(events_map){
// $('.events_map').each(function(){
//     var events_map = $(this);
//     // var $emb_map = $(this).find('.events_block_map');
//     // var $emb_filter = $(this).find('.events_block_filter');
//     // var $emb_list = $(this).find('.events_block_list');
//     var emb_panel = $('<div class="events_block events_block_panel"></div>');
//     var emb_button = $('<button class="NewEventsMap_btn" data-state="open">[ Показать на карте ]</button>');
//     emb_button.click( function() { ClickEventsMapBtn(events_map, $(this)); } )
//     emb_panel.append(emb_button);
//     events_map.append(emb_panel);
//     // events_map.before(emb_panel);
// });

[].forEach.call( document.querySelectorAll('.events_map'), function(events_map){
    // const markup = `
    //     <div class="events_block events_block_panel">
    //         <button class="NewEventsMap_btn" data-state="open">[ Показать на карте ]</button>
    //     </div>`;

    // events_map.insertAdjacentHTML('beforeend', markup);

    events_map.style.display = 'block';
    // events_map.querySelector('.events_block_panel').style.height = '30px';

    events_map.addEventListener('click', function(e){
        if( e.target.matches('.NewEventsMap_btn') ) {
            ClickEventsMapBtn(events_map, e.target);
        }
    });
});

function ClickEventsMapBtn(events_map, button){
    // if( button.attr('data-state') == 'open' ){
    if( button.dataset.state == 'open' ){
        if( no_events_map ){
            // var api_url = "https://api-maps.yandex.ru/2.1/?lang=ru_RU";
            // var events_map_js = "/wp-content/themes/excursions/assets/js/events_map.js";
            // $.getScript(api_url).then(function(){
                    // $.getScript(events_map_js);
                // }).then(function(){
                    NewEventsMap(events_map);
                    no_events_map = false;									
                // });
        }

        // button.attr('data-state', 'close');
        button.dataset.state = 'close';
        // button.text('[Закрыть карту]');
        button.innerHTML ='[Закрыть карту]';
        // events_map.find('.events_map_content').show();
        events_map.querySelector('.events_map_content').style.display = 'block';
        // $('#nav-block').removeClass('fixable'); // NavBlock
        NavBlock.classList.remove('fixable');
    }
    else{
        // button.attr('data-state', 'open');
        // button.text('[Открыть карту]');
        // events_map.find('.events_map_content').hide();
        // $('#nav-block').addClass('fixable'); // NavBlock
        button.dataset.state = 'open';
        button.innerHTML ='[Открыть карту]';
        events_map.querySelector('.events_map_content').style.display = 'none';
        NavBlock.classList.add('fixable');
    }
}

function NewEventsMap(events_map){
    
    // const em_content = $('<div class="events_map_content"></div>');
    // const emb_map    = $('<div class="events_block events_block_map"></div>');
    // const emb_filter = $('<div class="events_block events_block_filter"></div>');
    // const emb_list   = $('<div class="events_block events_block_list"></div>');
    // em_content.append(emb_map, emb_filter, emb_list);
    // events_map.append(em_content);
    const em_content = document.createElement('div');
    em_content.className = "events_map_content";
    const emb_map = document.createElement('div');
    emb_map.className = "events_block events_block_map";
    const emb_filter = document.createElement('div');
    emb_filter.className = "events_block events_block_filter";
    const emb_list = document.createElement('div');
    emb_list.className = "events_block events_block_list";

    em_content.appendChild(emb_map);
    em_content.appendChild(emb_filter);
    em_content.appendChild(emb_list);
    events_map.appendChild(em_content);

    // const markup = `
    //     <div class="events_map_content">
    //         <div class="events_block events_block_map"></div>
    //         <div class="events_block events_block_filter"></div>
    //         <div class="events_block events_block_list"></div>
    //     </div>`;
    // events_map.insertAdjacentHTML('beforeend', markup);
    
    // jQuery.ajax({
    //     // method: 'GET', // jQuery.ajax() default
    //     method: 'POST',
    //     url:    myajax.url,
    //     data:   { action: 'get_events' },
    //     success: function(response){
    //         const events = jQuery.parseJSON(response);
    //         ymaps.ready( function(){ 
    //             map = NewMap(emb_map, events);
    //             storage = ymaps.geoQuery(map.markers);
    //         });
    //         NewEventsList(emb_list, events);
    //     }
    // });

    // Must be scoped in an async function
    // const response = await fetch( myajax.url, fetchInit );
    // const events = await response.json();

    const fetchInit = { 
        method: 'POST',
        // headers: { 'content-type': 'application/x-www-form-urlencoded; charset=UTF-8', 'x-requested-with': 'XMLHttpRequest' },
        headers: { 'content-type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: 'action=get_events'
    };

    // $.getScript(api_url).then(function(){
    getScript(ymaps_api_url).then( () => {
        fetch( myajax.url, fetchInit )
        .then( response => response.json() )
        .then( events => {
            // console.log(events);
            // Функция ymaps.ready() будет вызвана, когда загрузятся все компоненты API, а также когда будет готово DOM-дерево.
            ymaps.ready( () => { 
                map = NewMap(emb_map, events);
                // storage = ymaps.geoQuery(map.geoObjects);
                storage = ymaps.geoQuery(map.markers);
            });
            NewEventsList(emb_list, events);
        })
    });

}

function NewMap(emb_map, events) {

    // const evMap = $('<div class="ev-map"></div>');
    // emb_map.append(evMap);
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

    // var $markers = $emb_map.find('.marker');
    // $markers.each(function(){ add_marker( $(this), map ); });
    // events.each( function(event){ add_marker( event, map ); });
    const ev_length = events.length;
    for(i = 0; i < ev_length; i++){ 
        add_marker( events[i], map ); 
    }

    // Кластеризация и размещение геообъектов на карте.
    // map.geoObjects.add(marker);
    clusterer = new ymaps.Clusterer({ 
        clusterIconColor: baseColor 
    });
    clusterer.add(map.markers);
    map.geoObjects.add(clusterer);

    // center_map( map );
    if(ev_length > 0){
        map.setBounds(
            map.geoObjects.getBounds(), 
            { checkZoomRange: true }
            ).then(function(){ 
                if(map.getZoom() > 16) map.setZoom(16); 
            });
    }

    return map;
}

function add_marker(event, map) {

    const lat     = event.lat;
    const lng     = event.lng;
    const post_id = event.post_id;
    const url     = event.permalink;
    const title   = event.title;

    // Макеты балуна и хинта
    const EventBalloonLayoutClass = ymaps.templateLayoutFactory.createClass(
        '<h3><a href="{{ properties.url }}">{{ properties.title }}</a></h3>'
    );

    const EventHintLayoutClass = ymaps.templateLayoutFactory.createClass(
        '<p>{{ properties.title }}</p>'
        // + '<p>{{ properties.post_id }}</p>'
    );

    // Создание геообъекта с типом точка (метка)
    const marker = new ymaps.Placemark(
        [lat, lng],
        {
            post_id: post_id,
            url: url,
            title: title
        },
        {
            // preset: 'islands#darkBlueIcon',
            preset: 'islands#Icon',
            iconColor: baseColor, 
            balloonContentLayout: EventBalloonLayoutClass,
            hintContentLayout: EventHintLayoutClass
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

function NewEventsList( emb_list, events ){
    // const events_list = $('<ul class="events_list"></ul>');
    const events_list = document.createElement('ul');
    events_list.className = "events_list";

    // const ev_length = events.length;
    // for(i = 0; i < ev_length; i++){
    //     const events_li = $('<li class="events_li"></li>');
    //     events_li.attr('data-post_id', events[i].post_id);
    //     events_li.text(events[i].title);
    //     const hiddenlink = $('<a class="hiddenlink"></a>');
    //     hiddenlink.attr('href', events[i].permalink);
    //     hiddenlink.text('[Перейти\u00A0>>]');
    //     events_li.append(hiddenlink);
    //     events_list.append(events_li);
    //     AddListenersToEventsLi(events_li);
    // }

    events.forEach( function(event){
        const hiddenlink = `<a class="hiddenlink" href="${event.permalink}">[Перейти\u00A0>>]</a>`;
        
        // <li class="events_li" data-post_id="${event.post_id}">${event.title}</li>
        const events_li = document.createElement('li');
        events_li.className = "events_li"
        // events_li.setAttribute('data-post_id', event.post_id);
        events_li.dataset.post_id = event.post_id;
        events_li.innerHTML = event.title;
        events_li.insertAdjacentHTML('beforeend', hiddenlink);
        AddListenersToItem(events_li);

        events_list.appendChild(events_li);
    });

    // emb_list.append(events_list);
    emb_list.appendChild(events_list);
}

function AddListenersToItem(item){
    item.addEventListener('mouseenter', function(e){
        if(e.target.matches('[data-post_id]')) {
            const post_id = e.target.dataset.post_id;
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
        }
    });

    // events_li.mouseleave( function(e){
    item.addEventListener('mouseleave', function(e){
        // if(e.target.matches('.events_li')) {
        if(e.target.matches('[data-post_id]')) {
            const post_id = e.target.dataset.post_id;
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
        }
    });
}
