/*
    script.js: 
    $('.events_map').each(function() > ClickEventsMapBtn(events_map, button) > NewEventsMap(events_map);
*/

var NewEventsMap = function(events_map){
    
    var em_content = $('<div class="events_map_content"></div>');
    var emb_map = $('<div class="events_block events_block_map"></div>');
    var emb_filter = $('<div class="events_block events_block_filter"></div>');
    var emb_list = $('<div class="events_block events_block_list"></div>');
    em_content.append(emb_map, emb_filter, emb_list);
    events_map.append(em_content);

    $.ajax({
        // method: 'GET',
        url:    myajax.url,
        data:   { action: 'get_events' },

        success: function(response){
            var events = jQuery.parseJSON(response);
            // console.log(events);
            // Функция ymaps.ready() будет вызвана, когда
            // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
            ymaps.ready( function(){ 
                map = NewMap(emb_map, events);
                storage = ymaps.geoQuery(map.geoObjects);
            });

            // emb_filter.text(' events_num = ' + events.length);
            NewEventsList(emb_list, events);
        } 
    });
}

function NewMap(emb_map, events) {

    var evMap = $('<div class="ev-map"></div>');
    emb_map.append(evMap);

    var map = new ymaps.Map( evMap[0], { 
        center: [52.967631, 36.069584], 
        zoom: 12
    });
    
    if(screen.width <= 768){
        map.behaviors.disable('scrollZoom');
    }
    
    // var $markers = $emb_map.find('.marker');
    // $markers.each(function(){ add_marker( $(this), map ); });
    var ev_length = events.length;
    for(i = 0; i < ev_length; i++){
        add_marker( events[i], map );
    }

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

    var lat     = event.lat;
    var lng     = event.lng;
    var post_id = event.post_id;
    var url     = event.permalink;
    var title   = event.title;

    // Макеты балуна и хинта
    var EventBalloonLayoutClass = ymaps.templateLayoutFactory.createClass(
        '<h3><a href="{{ properties.url }}">{{ properties.title }}</a></h3>'
    );

    var EventHintLayoutClass = ymaps.templateLayoutFactory.createClass(
        '<p>{{ properties.title }}</p>'
        // + '<p>{{ properties.post_id }}</p>'
    );

    // Создание геообъекта с типом точка (метка)
    var marker = new ymaps.Placemark(
        [lat, lng],
        {
            post_id: post_id,
            url: url,
            title: title
        },
        {
            // preset: 'islands#darkBlueIcon',
            preset: 'islands#Icon',
            iconColor: '#005281', // 015a8d
            balloonContentLayout: EventBalloonLayoutClass,
            hintContentLayout: EventHintLayoutClass
        }
    );

    marker.events.add('mouseenter', function (e) {
        var mark = e.get('target');
        mark.options.set('iconColor', '#bc3134'); // ffd649
    });

    marker.events.add('mouseleave', function (e) {
        var mark = e.get('target');
        mark.options.set('iconColor', '#005281');
    });

    // Размещение геообъекта на карте.
    map.geoObjects.add(marker);

}

var NewEventsList = function( emb_list, events ){
    var events_list = $('<ul class="events_list"></ul>');

    var ev_length = events.length;
    for(i = 0; i < ev_length; i++){
        var events_li = $('<li class="events_li"></li>');
        events_li.attr('data-post_id', events[i].post_id);
        events_li.text(events[i].title);
        var hiddenlink = $('<a class="hiddenlink"></a>');
        hiddenlink.attr('href', events[i].permalink);
        hiddenlink.text('[Перейти]');
        
        events_li.append(hiddenlink);

        events_list.append(events_li);

        AddListenersToEventsLi(events_li);
    }

    emb_list.append(events_list);
}

var AddListenersToEventsLi = function( events_li ){

    events_li.mouseenter( function(){
        var post_id = $(this).attr('data-post_id');
        // $(this).text( post_id );
        storage.each( function(mark){
            if( mark.properties.get('post_id') == post_id ){
                mark.options.set('iconColor', '#bc3134');
                // mark.hint.open();
            }
        });
    });

    events_li.mouseleave( function(){
        var post_id = $(this).attr('data-post_id');
        // $(this).text( post_id );
        storage.each( function(mark){
            if( mark.properties.get('post_id') == post_id ){
                mark.options.set('iconColor', '#005281');
                // mark.hint.close();
            }
        });
    });
}
