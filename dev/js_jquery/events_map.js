/*
    script.js: 
    $('.events_map').each(function() > ClickEventsMapBtn(events_map, button) > NewEventsMap(events_map);
*/
const baseColor = '#005281'; // 015a8d
const activeColor = '#bc3134'; // ffd649

// const productsContainer = document.querySelector('#productsMainContainer');

const NewEventsMap = function(events_map){
    
    const em_content = $('<div class="events_map_content"></div>');
    const emb_map    = $('<div class="events_block events_block_map"></div>');
    const emb_filter = $('<div class="events_block events_block_filter"></div>');
    const emb_list   = $('<div class="events_block events_block_list"></div>');
    em_content.append(emb_map, emb_filter, emb_list);
    events_map.append(em_content);

    $.ajax({
        // method: 'GET',
        url:    myajax.url,
        data:   { action: 'get_events' },

        success: function(response){
            const events = jQuery.parseJSON(response);
            // console.log(events);
            // Функция ymaps.ready() будет вызвана, когда
            // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
            ymaps.ready( function(){ 
                map = NewMap(emb_map, events);
                // storage = ymaps.geoQuery(map.geoObjects);
                storage = ymaps.geoQuery(map.markers);
                // console.log( storage );
            });

            // emb_filter.text(' events_num = ' + events.length);
            NewEventsList(emb_list, events);
        } 
    });
}

function NewMap(emb_map, events) {

    const evMap = $('<div class="ev-map"></div>');
    emb_map.append(evMap);

    const map = new ymaps.Map( evMap[0], { 
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
    marker.events.add('mouseenter', function (e) {
        const mark = e.get('target');
        mark.options.set('iconColor', activeColor); 
    });

    marker.events.add('mouseleave', function (e) {
        const mark = e.get('target');
        mark.options.set('iconColor', baseColor);
    });

    marker.events.add('balloonopen', function (e) {
        const mark = e.get('target');
        mark.options.set('iconColor', activeColor); 
    });

    marker.events.add('balloonclose', function (e) {
        const mark = e.get('target');
        mark.options.set('iconColor', baseColor);
    });

    // Добавляем новый геообъект в массив
    map.markers.push(marker);

    // Размещение геообъекта на карте.
    // map.geoObjects.add(marker);

}

const NewEventsList = function( emb_list, events ){
    const events_list = $('<ul class="events_list"></ul>');

    const ev_length = events.length;
    for(i = 0; i < ev_length; i++){
        const events_li = $('<li class="events_li"></li>');
        events_li.attr('data-post_id', events[i].post_id);
        events_li.text(events[i].title);
        const hiddenlink = $('<a class="hiddenlink"></a>');
        hiddenlink.attr('href', events[i].permalink);
        hiddenlink.text('[Перейти\u00A0>>]');
        
        events_li.append(hiddenlink);

        events_list.append(events_li);

        AddListenersToEventsLi(events_li);
    }

    emb_list.append(events_list);
}

// const events_map_sel = document.getElementsByClassName('events_map');

const AddListenersToEventsLi = function( events_li ){

    events_li.mouseenter( function(e){
        // if(e.target.matches('events_li')) {
            // const post_id = $(this).attr('data-post_id');
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
        // }
    });

    events_li.mouseleave( function(e){
        // if(e.target.matches('events_li')) {
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
        // }
    });
}
