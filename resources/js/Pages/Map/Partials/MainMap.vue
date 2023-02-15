<script setup>
import {onMounted} from "vue";
import {hotspotLayer, imgLayer, mainMap, streetLayer} from "../../../Stores/yandexMapStore.js";
import {filter} from "@/Stores/filterStore.js";
import {getStat} from "@/Stores/mapRegionStatStore.js";

onMounted(() => {
    ymaps.ready(initMainMap);
});

function initMainMap(){
        // Add layers
        mainMap.layers.add(imgLayer);
        mainMap.layers.add(hotspotLayer);
        mainMap.layers.add(streetLayer);
        getStat(mainMap);

        // refresh filter to apply it to balloon and magnify
        filter.form.zoom = mainMap.getZoom();
        filter.form.lat = mainMap.getCenter()[0];
        filter.form.lon = mainMap.getCenter()[1];
        filter.refresh();

        // ADD EVENTS
        // Add dynamically balloon loading
        hotspotLayer.events.add('click', function (e) {
            const object = e.get('activeObject');
            let quadkey = object.getProperties().quad;
            // object.getProperties().balloonContentHeader = 'NEW';

            fetch("/get-hotspot-balloon?quadkey=" + quadkey + '&' + filter.queryString).then(function (response) {
                response.text().then(function (features) {
                    features = JSON.parse(features);
                    hotspotLayer.balloon.setData(features)
                });
            });
        });

        // Add magnify image loading with delay
        let hoverTimer;

        hotspotLayer.events.add('mouseenter', function (e) {
            if (document.getElementById('magnify-container').style.display !== 'none') {
                clearTimeout(hoverTimer);
                hoverTimer = setTimeout(function () {
                    let object = e.get('activeObject');
                    let tileNumber = object.getProperties().tileNumber;
                    let magnifyPoints = document.querySelectorAll('#magnify-points')[0];
                    let magnifyYmap = document.querySelectorAll('#magnify-ymap')[0];
                    magnifyYmap.style.backgroundImage
                        = "url('https://core-renderer-tiles.maps.yandex.net/tiles?l=map&x=" + tileNumber['x'] + "&y=" + tileNumber['y'] + "&z=" + tileNumber['z'] + "')";
                    magnifyPoints.style.backgroundImage
                        = "url('/storage/tiles/png/" + filter.key + "/" + tileNumber['z'] + "/" + tileNumber['x'] + "_" + tileNumber['y'] + ".png?" + filter.queryString + "' )";
                }, 300);
            }
        });

        hotspotLayer.events.add('mouseleave', function () {
            clearTimeout(hoverTimer);
        });

        // Add coords and zoom sending to map filter via form input elements
        mainMap.events.add(['boundschange'], function () {
            filter.form.zoom = mainMap.getZoom();
            filter.form.lat = mainMap.getCenter()[0];
            filter.form.lon = mainMap.getCenter()[1];
            filter.refresh();
            getStat(mainMap);
        });

        // HOVER SQUARE
        let squareLayout = ymaps.templateLayoutFactory.createClass('<div class="hover_square" style="background-color: rgba(0,255,0,0.3);width: 16px;height: 16px"></div>');
        let squarePlacemark = new ymaps.Placemark([0, 0], {}, {iconLayout: squareLayout});
        mainMap.geoObjects.add(squarePlacemark);
        hotspotLayer.events.add('mouseenter', function (e) {
            let z = mainMap.getZoom();
            let coordPoint = e.get('coords');
            let pixelCoords = ymaps.projection.wgs84Mercator.toGlobalPixels(coordPoint, z);

            let globalPixelPoint = [Math.floor(pixelCoords[0] / 16) * 16, Math.floor(pixelCoords[1] / 16) * 16];
            let geoCoords = ymaps.projection.wgs84Mercator.fromGlobalPixels(globalPixelPoint, z);

            squarePlacemark.geometry.setCoordinates(geoCoords);
        });
        hotspotLayer.events.add(['mouseleave', 'wheel'], function () {
            squarePlacemark.geometry.setCoordinates([0, 0]);
        });
}
</script>

<template>
    <div id="main_map" class="w-full h-full min-h-[calc(100vh-110px)]"></div>
</template>

<style>
/* Map to gray */
/*noinspection ALL*/
[class*="ymaps-2"][class*="-ground-pane"] > ymaps:first-child {
    filter: grayscale(1);
    -ms-filter: grayscale(1);
    -webkit-filter: grayscale(1);
    -moz-filter: grayscale(1);
    -o-filter: grayscale(1);
}

/* Street layer */
[class*="ymaps-2"][class*="-ground-pane"] > ymaps:nth-child(3) {
    opacity: 0;
    filter: grayscale(1);
    /*display: none;*/
}

.ymaps-2-1-79-map-copyrights-promo,
.ymaps-2-1-79-copyright__agreement {
    display: none;
}
</style>
