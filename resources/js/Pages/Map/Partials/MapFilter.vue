<script setup>
import Multiselect from 'vue-multiselect'
import {router, useForm} from "@inertiajs/vue3";
import {onMounted, ref} from 'vue'
import {filter} from "../../../Stores/filterStore.js"
import {mainMap, imgLayer, hotspotSource, hotspotLayer} from "../../../Stores/yandexMapStore.js";
import {getStat} from "../../../Stores/mapRegionStatStore.js";
import MapFilterCustomMultiselect from "./MapFilterCustomMultiselect.vue";
import StreetLayerSlider from "./StreetLayerSlider.vue";

let props = defineProps({
    years: Object,
    participant_categories: Object,
    severities: Object,
    accident_categories: Object,
    weather_conditions: Object,
    light_conditions: Object,
});

onMounted(() => {
    setQueryArgsToFilterForm();
});

let setQueryArgsToFilterForm = () => {
    let uri = router.page.url.split('?');

    if (uri.length === 2) {
        let vars = uri[1].split('&');
        let tmp = '';

        vars.forEach(function (v) {
            tmp = v.split('=');

            if (tmp.length === 2) {
                if (tmp[0] === 'period') filter.form.period = tmp[1];
                if (tmp[0].startsWith('severities')) filter.form.severities.push(tmp[1]);
                if (tmp[0].startsWith('participant_categories')) filter.form.participant_categories.push(tmp[1]);
                if (tmp[0].startsWith('accident_categories')) filter.form.accident_categories.push(tmp[1]);
                if (tmp[0].startsWith('zoom')) filter.form.zoom = tmp[1];
                if (tmp[0].startsWith('lat')) filter.form.lat = tmp[1];
                if (tmp[0].startsWith('lon')) filter.form.lon = tmp[1];
            }
        });
    }
};

function submit() {
    filter.refresh();
    imgLayer.setTileUrlTemplate('storage/tiles/png/' + filter.key + '/%z/%x_%y.png?' + filter.queryString)
    imgLayer.update();
    hotspotSource.setTileUrlTemplate('storage/tiles/hotspot/' + filter.key + '/%z/%x_%y.js?' + filter.queryString)
    hotspotLayer.update();
    getStat(mainMap);
}

let filterCollapse = ref(false);

function toggleFilter() {
    filterCollapse.value = !filterCollapse.value;
}

let activeFilters = () => {
    let arr = [];

    if(filter.form.participant_categories.length) arr.push('Участники ДТП');
    if(filter.form.severities.length) arr.push('Вред здоровью');
    if(filter.form.accident_categories.length) arr.push('Типы ДТП');
    if(filter.form.light_conditions.length) arr.push('Освещение');

    return arr;
}
</script>

<template>
    <div id="map-filter"
         class="top-16 absolute md:m-4 m-2 rounded-lg bg-white text-[rgb(24,51,74)] text-xs w-[400px] z-50 shadow-md">
        <form class="p-6 overflow-hidden">
            <div class="flex justify-between">
                <label class="" for="period">Период данных</label>
            </div>

            <!--  PERIOD SELECT  -->
            <select @change="submit" v-model="filter.form.period"
                    class="mt-2 w-full rounded p-2 bg-gray-100 font-sans border-none focus:ring-0 mb-6"
                    name="period"
                    id="period"
            >
                <option value=null selected>2015 - 2022</option>
                <option v-for="year in years" :value="year">{{ year }}</option>
            </select>

            <!--  ACTIVE FILTER WARNING  -->
            <div v-show="filterCollapse && activeFilters().length">
                <p class="text-[11px] font-semibold">Активные фильтры</p>
                <div class="my-3">{{ activeFilters().join(', ') }}</div>
            </div>

            <!--------  COLLAPSED AREA ---------->
            <div v-show="!filterCollapse">
                <!--  PARTICIPANT CATEGORIES CHECKBOXES  -->
                <p>Участники ДТП</p>
                <div class="mt-4 grid grid-cols-3  w-full participant-categories">
                    <div v-for="participantCategory in participant_categories">
                        <input @change="submit" class="hidden"
                               :id="'participant_categories[' + participantCategory['id'] + ']'"
                               name="participant_categories[]" type="checkbox" :value="participantCategory['id']"
                               v-model="filter.form.participant_categories">
                        <label :for="'participant_categories[' + participantCategory['id'] + ']'"
                               class="mb-3 flex flex-col mx-1 w-[112px] p-3 h-[65px] bg-slate-50 hover:bg-[#8192a0] hover:text-white cursor-pointer text-center rounded-lg">
                            <i :class="' text-xl icon-dtp-' + participantCategory['icon']"></i>
                            <span class="leading-4 text-[9px] font-semibold">{{ participantCategory['name'] }}</span>
                        </label>
                    </div>
                </div>

                <!--  SEVERITIES CHECKBOXES  -->
                <div class="mt-1 mb-3">
                    <p class="mb-2">Вред здоровью</p>
                    <div v-for="severity in severities" class="mt-1 flex items-center space-x-2">
                        <input @change="submit" class="" :id="'severities[' + severity.id + ']'" name="severities[]"
                               v-model="filter.form.severities"
                               type="checkbox" :value="severity.id">
                        <label :for="'severities[' + severity.id + ']'" class="flex items-center cursor-pointer">
                            <div :style="'background-color: ' + severity.color" class="rounded-full w-3 h-3 mr-1"></div>
                            {{ severity.name }}
                        </label>
                    </div>
                </div>

                <p>Фильтры</p>
                <!--  ACCIDENT CATEGORIES MULTISELECT  -->
                <MapFilterCustomMultiselect @submit="submit" name="Типы ДТП" :options="accident_categories"
                                            field="accident_categories"/>

                <!--  ACCIDENT CATEGORIES MULTISELECT  -->
                <MapFilterCustomMultiselect @submit="submit" name="Освещение" :options="light_conditions"
                                            field="light_conditions"/>

                <!--  Street Layer Range Slider -->
                <StreetLayerSlider class="mt-4"/>

                <!--  MAGNIFY  -->
                <div id="magnify-container" class="mr-2 mb-6 absolute right-0 bottom-0 border z-30 bg-gray-100"
                     style="width: 220px; height: 220px; background-size: 100%">
                    <div id="magnify-ymap" class="absolute w-full h-full" style="background-size: 100%"></div>
                    <div id="magnify-points" class="absolute w-full h-full" style="background-size: 100%"></div>
                </div>
            </div>
            <!-------- / COLLAPSED AREA ---------->

            <!-- zoom-coords hidden inputs -->
            <div class="flex flex-col space-y-2">
                <input v-model="filter.form.zoom" id="zoom" name="zoom"
                       class="hidden mt-6 w-6 h-6 bg-gray-200 text-center">
                <input v-model="filter.form.lat" id="lat" name="lat" class="hidden bg-gray-200">
                <input v-model="filter.form.lon" id="lon" name="lon" class="hidden bg-gray-200">
            </div>
        </form>

        <!--  toggleFilter  -->
        <div :onclick="toggleFilter"
             class="absolute z-[100] -bottom-2.5 left-[160px] rounded-lg px-3 bg-gray-200 font-semibold text-xs py-1 hover:cursor-pointer">
            <i class="material-symbols-outlined text-xs"> {{ filterCollapse ? 'expand_more' : 'expand_less' }}</i>
            {{ filterCollapse ? 'Показать' : 'Скрыть' }}
        </div>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
div.participant-categories input:checked + label {
    background-color: #586c7c;
    color: white;
}

/*noinspection CssInvalidPropertyValue*/
#magnify-ymap {
    filter: grayscale(1);
    -ms-filter: grayscale(1);
    -webkit-filter: grayscale(1);
    -moz-filter: grayscale(1);
    -o-filter: grayscale(1);
}

</style>
