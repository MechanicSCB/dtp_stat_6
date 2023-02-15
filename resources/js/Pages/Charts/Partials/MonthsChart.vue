<script setup>
import {Line} from 'vue-chartjs'
import {Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend} from 'chart.js'
import {computed} from "vue";

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

let props = defineProps({
    monthsChartData: Object,
});

const chartData = computed(function (){
    return {
        labels: props.monthsChartData.labels,
        datasets: [
            {
                label: 'число ДТП',
                backgroundColor: '#509EE3',
                data: props.monthsChartData.number_of_points
            },
            {
                label: 'число погибших',
                backgroundColor: '#DC2626',
                data: props.monthsChartData.dead_count
            },
            {
                label: 'число пострадавших',
                backgroundColor: '#F97316',
                data: props.monthsChartData.injured_count
            },
        ],
    };
})

let chartOptions = {
    responsive: true,
    aspectRatio:1|3,
};
</script>

<template>
    <div class="w-full aspect-[3/1]">
        <Line :options="chartOptions" :data="chartData"/>
    </div>
</template>
