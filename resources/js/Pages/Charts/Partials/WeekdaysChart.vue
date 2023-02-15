<script setup>
import {Bar} from 'vue-chartjs'
import {Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend} from 'chart.js'
import {computed} from "vue";

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

let props = defineProps({
    weekdaysChartData: Object,
});

const chartData = computed(function (){
    return {
        labels: props.weekdaysChartData.labels,
        datasets: [
            {
                label: 'число ДТП',
                backgroundColor: '#509EE3',
                data: props.weekdaysChartData.number_of_points
            },
            {
                label: 'число погибших',
                backgroundColor: '#DC2626',
                data: props.weekdaysChartData.dead_count
            },
            {
                label: 'число пострадавших',
                backgroundColor: '#F97316',
                data: props.weekdaysChartData.injured_count
            },
        ],
    };
})

let chartOptions = {
    responsive: true,
    aspectRatio:1|1,
};
</script>

<template>
    <div class="aspect-[1/1]">
        <Bar :options="chartOptions" :data="chartData"/>
    </div>
</template>
