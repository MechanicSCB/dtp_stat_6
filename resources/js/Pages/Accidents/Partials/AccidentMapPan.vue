<script setup>
import {onMounted, ref} from 'vue';

const toggleButtonsKey = ref(0);

const forceRerender = () => {
    toggleButtonsKey.value += 1;
};

let props = defineProps({
    accident: Object,
});

let isPan = ref(false);
let hasPan = ref(false);

function toggleMapPan() {
    isPan.value = !isPan.value;
}

let accident = props.accident;

onMounted(() => {
    let coords = [accident['point']['lat'], accident['point']['long']];
    ymaps.ready(init);

    function init() {
        var accidentMap = new ymaps.Map('map', {
                center: [accident['point']['lat'] + 0.0005, accident['point']['long']],
                zoom: 16,
                controls: ['zoomControl'],
            }, {
                yandexMapDisablePoiInteractivity: true,
            }),
            myGeoObject = new ymaps.GeoObject({
                geometry: {
                    type: "Point",
                    coordinates: coords,
                },
                properties: {
                    balloonContent: accident['point']['lat'] + ', ' + accident['point']['long'],
                },
            }, {
                iconLayout: 'default#image', // Своё изображение иконки метки.
                iconImageHref: '/images/accident-page/pin.svg', // Размеры метки.
                iconImageSize: [36, 50], // Смещение левого верхнего угла иконки относительно
                iconImageOffset: [-18, -50],
            });

        accidentMap.behaviors.disable('scrollZoom');
        accidentMap.geoObjects.add(myGeoObject);

        if (ymaps.panorama.isSupported()) {
            // Получение объекта Panorama.

            var locateRequest = ymaps.panorama.locate(coords);

            // Функция ymaps.panorama.locate возвращает Promise-объект,
            // который разрешится массивом с найденной панорамой либо пустым
            // массивом, если в окрестностях точки панорам не нашлось.
            locateRequest.then(
                function (panoramas) {
                    if (panoramas.length) {
                        hasPan = true;
                        forceRerender()
                        ymaps.panorama.createPlayer(
                            'panorama',
                            coords,
                            {layer: 'yandex#panorama'},
                        )
                            .done(function (player) {
                                // player – это ссылка на экземпляр плеера.
                            });

                    } else {
                        hasPan = false;
                        //console.log("Для заданной точки не найдено ни одной панорамы.");
                    }
                },
                function (err) {
                    //console.log("При попытке получить панораму возникла ошибка.");
                },
            );
        }
    }
});
</script>

<template>
    <div class="accident-page text-[#18334A]">
        <div id="toggle" class="h-[280px] relative text-[18px]">
            <div class="w-full h-full" id="map"></div>
            <div id="panorama" class="absolute top-0 w-full h-full" :class="{'invisible':!isPan}"></div>
            <div class="bottom-[18px] absolute flex w-full z-40">
                <a :href="'/?zoom=15&lat='+accident['point']['lat']+'&lon='+accident['point']['long']" target="_blank"
                   class="mx-auto bg-white bg-opacity-75 flex items-center space-x-1 flex-initial px-4 py-1.5 rounded-full font-medium shadow-lg">
                    <i class="material-symbols-outlined">map</i>
                    Показать ДТП рядом
                </a>
            </div>
            <div :key="toggleButtonsKey">
                <div v-if="hasPan" class="relative -mt-14 float-right flex z-40 mr-2">
                    <button :onclick="toggleMapPan"
                            class="flex items-center space-x-1 flex-initial px-4 py-1.5 rounded-full font-medium shadow-lg"
                            :class="!isPan ?'text-white bg-[#18334A] z-10':'text-[#18334A]  bg-[rgba(255,255,255,0.9)]'">
                        <i class="material-symbols-outlined">location_on</i>
                        <span>Карта</span>
                    </button>
                    <button :onclick="toggleMapPan"
                            class="-ml-3 flex items-center space-x-1 flex-initial px-4 py-1.5 rounded-full font-medium shadow-lg"
                            :class="isPan ?'text-white bg-[#18334A]':'text-[#18334A]  bg-[rgba(255,255,255,0.9)]'">
                        <i class="material-symbols-outlined">videocam</i>
                        <span>Панорама</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.active-toggle-btn {
    background-color: #18334A;
    color: white;
    z-index: 10;
}

.material-symbols-outlined {
    font-variation-settings: 'FILL' 1,
    'wght' 400,
    'GRAD' 0,
    'opsz' 48
}

.ymaps-2-1-79-copyrights-pane,
.ymaps-2-1-79-panorama-control__copyright,
.ymaps-2-1-79-panorama-control__close {
    display: none !important;
}

.ymaps-2-1-79-panorama-control__zoom {
    bottom: 80px !important;
}

[class*="ymaps-2"][class*="-ground-pane"] > ymaps:first-child {
    filter: grayscale(1);
    -ms-filter: grayscale(1);
    -webkit-filter: grayscale(1);
    -moz-filter: grayscale(1);
    -o-filter: grayscale(1);
}

/* PANORAMA CONTROL BUTTONS */
.ymaps-2-1-79-islets_round-button__icon{
    width: 100% !important;
    height: 100% !important;
}
</style>
