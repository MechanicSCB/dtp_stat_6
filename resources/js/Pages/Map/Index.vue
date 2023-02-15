<script setup>
import MainMap from "./Partials/MainMap.vue";
import MapFilter from "./Partials/MapFilter.vue";
import MapStat from "./Partials/MapStat.vue";

let props = defineProps({
    years: Array,
    participant_categories: Object,
    severities: Object ,
    accident_categories: Object ,
    weather_conditions: Object ,
    light_conditions: Object ,
});

</script>

<template>
    <Head title="Карта ДТП" />
    <MapFilter :years="years" :participant_categories="participant_categories" :severities="severities"
               :accident_categories="accident_categories" :weather_conditions="weather_conditions" :light_conditions="light_conditions"/>
    <MapStat/>
    <MainMap :years="years" :participant_categories="participant_categories" :severities="severities"/>
</template>
