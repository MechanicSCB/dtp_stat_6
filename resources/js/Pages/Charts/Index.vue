<script setup>
import {Link} from "@inertiajs/vue3"
import ChartsFilter from "@/Pages/Charts/Partials/ChartsFilter.vue";
import SquaredStat from "./Partials/SquaredStat.vue";
import SeveritiesChart from "./Partials/SeveritiesChart.vue";
import LightConditionsChart from "./Partials/LightConditionsChart.vue";
import MonthsChart from "./Partials/MonthsChart.vue";
import WeekdaysChart from "./Partials/WeekdaysChart.vue";
import HoursChart from "./Partials/HoursChart.vue";

let props = defineProps({
    filterFormData: Object,
    chartsData: Object,
    time: String,
});
</script>

<template>
    <div class="max-w-[898px] min-w-[500px] mx-auto text-center text-[#4c5773] pb-12">
        <h1 class="mt-12 text-3xl font-bold">Статистика ДТП</h1>
        <div class="mt-6 mx-4 border rounded px-2 flex flex-wrap gap-2 pb-4">
            <h2 class="mt-8 w-full text-lg font-bold text-left pl-2">ДТП</h2>
            <ChartsFilter :filterFormData="filterFormData"/>
            <SquaredStat :mainStat="chartsData['main_stat'] ?? []"/>
            <SeveritiesChart class="border rounded" :severitiesChartData="chartsData['severities'] ?? []"/>
            <LightConditionsChart class="border rounded" :lightConditionsChartData="chartsData['light_conditions'] ?? []"/>
            <MonthsChart class="border rounded" :monthsChartData="chartsData['months'] ?? []"/>
            <WeekdaysChart class="border rounded" :weekdaysChartData="chartsData['weekdays'] ?? []"/>
            <HoursChart class="border rounded" :hoursChartData="chartsData['hours'] ?? []"/>
        </div>
    </div>
</template>
