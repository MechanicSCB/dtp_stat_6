export let mainMap, imgLayer, hotspotLayer, hotspotSource, streetLayer;
import {getUrlParameter} from "../Functions/getUrlParameter.js";
// import {getFilterCacheKey} from "../Functions/getFilterCacheKey.js";

let queryString = window.location.search.substring(1);
let filterKey = getFilterCacheKey(queryString);

ymaps.ready(function () {
    // MAIN MAP
    mainMap = new ymaps.Map('main_map', {
        center: [getUrlParameter('lat', queryString) || 55.761956, getUrlParameter('lon', queryString) || 37.618083], // Moscow
        zoom: getUrlParameter('zoom', queryString) || 12,
        controls: [],
    }, {
        yandexMapDisablePoiInteractivity: true,
        minZoom: 4,
        maxZoom: 17,
    });

    // Add control elements
    mainMap.controls.add('zoomControl', {float: 'none', position: {top: '50px', right: '5px'}});
    mainMap.controls.add('searchControl', {noPlacemark: true, float: 'none', position: {top: '5px', right: '5px'}});

    // IMG LAYER
    const mimetype = 'webp';
    const imgUrlTemplate = 'storage/tiles/' + mimetype + '/' + filterKey + '/%z/%x_%y.' + mimetype + '?' + queryString;
    imgLayer = new ymaps.Layer(imgUrlTemplate, {tileTransparent: true});

    // HOTSPOT
    const hotspotUrlTemplate = 'storage/tiles/hotspot/' + filterKey + '/%z/%x_%y.js?' + queryString;
    hotspotSource = new ymaps.hotspot.ObjectSource(hotspotUrlTemplate, 'testCallback_tile_%c');
    hotspotLayer = new ymaps.hotspot.Layer(hotspotSource, {cursor: 'help'});

    // streetLayer
    streetLayer = new ymaps.Layer('https://core-renderer-tiles.maps.yandex.net/tiles?l=skl&v=22.02.09-0-b220203150200&x=%x&y=%y&z=%z&scale=1&lang=ru_RU', {
        tileTransparent: true,
    })
});

