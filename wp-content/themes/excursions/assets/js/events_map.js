const baseColor="#005281",activeColor="#bc3134";function addMarker(a,b){const{lat:c}=a,{lng:d}=a,e=a.post_id,f=a.permalink,{title:g}=a,h=ymaps.templateLayoutFactory.createClass("<h3><a href=\"{{ properties.url }}\">{{ properties.title }}</a></h3>"),i=ymaps.templateLayoutFactory.createClass("<p>{{ properties.title }}</p>"),j=new ymaps.Placemark([c,d],{postId:e,url:f,title:g},{preset:"islands#Icon",iconColor:baseColor,balloonContentLayout:h,hintContentLayout:i});j.events.add("mouseenter",a=>{const b=a.get("target");b.options.set("iconColor",activeColor)}),j.events.add("mouseleave",a=>{const b=a.get("target");b.options.set("iconColor",baseColor)}),j.events.add("balloonopen",a=>{const b=a.get("target");b.options.set("iconColor",activeColor)}),j.events.add("balloonclose",a=>{const b=a.get("target");b.options.set("iconColor",baseColor)}),b.markers.push(j)}function NewMap(a,b,c){const d=document.createElement("div");d.className="ev-map",a.appendChild(d);const e=new ymaps.Map(d,{center:[52.967631,36.069584],zoom:12});768>=window.screen.width&&e.behaviors.disable("scrollZoom"),e.markers=[];const f=b.length;for(let d=0;d<f;d++)addMarker(b[d],e);return c.add(e.markers),e.geoObjects.add(c),0<f&&e.setBounds(e.geoObjects.getBounds(),{checkZoomRange:!0}).then(()=>{16<e.getZoom()&&e.setZoom(16)}),e}function AddListenersToItem(a,b,c){a.addEventListener("mouseenter",a=>{if(a.target.matches("[data-post_id]")){const d=a.target.dataset.post_id;b.search(`properties.postId = ${d}`).each(a=>{const b=c.getObjectState(a);b.isShown&&(b.isClustered?b.cluster.options.set("clusterIconColor",activeColor):a.options.set("iconColor",activeColor))})}}),a.addEventListener("mouseleave",a=>{if(a.target.matches("[data-post_id]")){const d=a.target.dataset.post_id;b.search(`properties.postId = ${d}`).each(a=>{const b=c.getObjectState(a);b.isShown&&(b.isClustered?b.cluster.options.set("clusterIconColor",baseColor):a.options.set("iconColor",baseColor))})}})}function NewEventsList(a,b,c,d){const e=document.createElement("ul");e.className="events_list",b.forEach(a=>{const b=`<a class="hiddenlink" href="${a.permalink}">[Перейти\u00A0>>]</a>`,f=document.createElement("li");f.className="events_li",f.dataset.post_id=a.post_id,f.innerHTML=a.title,f.insertAdjacentHTML("beforeend",b),AddListenersToItem(f,c,d),e.appendChild(f)}),a.appendChild(e)}function NewEventsMap(a){const b=document.createElement("div");b.className="events_map_content";const c=document.createElement("div");c.className="events_block events_block_map";const d=document.createElement("div");d.className="events_block events_block_filter";const e=document.createElement("div");e.className="events_block events_block_list",b.appendChild(c),b.appendChild(d),b.appendChild(e),a.appendChild(b);const f={method:"POST",headers:{"content-type":"application/x-www-form-urlencoded; charset=UTF-8"},body:"action=get_events"};getScript(ymapsApiUrl).then(()=>{fetch(myajax.url,f).then(a=>a.json()).then(a=>{ymaps.ready(()=>{const b=new ymaps.Clusterer({clusterIconColor:baseColor}),d=NewMap(c,a,b),f=ymaps.geoQuery(d.markers);NewEventsList(e,a,f,b)})})})}function ClickEventsMapBtn(a){switch(console.log("ClickEventsMapBtn"),a.dataset.state){case"init":console.log("init"),NewEventsMap(a),a.setAttribute("data-state","open");break;case"close":a.setAttribute("data-state","open");break;case"open":a.setAttribute("data-state","close");break;default:}"open"===a.dataset.state?NavBlock.classList.remove("fixable"):NavBlock.classList.add("fixable")}[].forEach.call(document.querySelectorAll(".events_map"),a=>{a.setAttribute("style","display: block;");const b=a.querySelector(".NewEventsMap_btn");b.addEventListener("click",()=>{ClickEventsMapBtn(a)})});