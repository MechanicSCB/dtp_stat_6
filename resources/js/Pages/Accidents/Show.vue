<script setup>
import AccidentMapPan from "./Partials/AccidentMapPan.vue";
import AccidentMainInfo from "./Partials/AccidentMainInfo.vue";
import ParticipantCard from "./Partials/ParticipantCard.vue";

let props = defineProps({
    accident: Object
});
</script>

<template>
    <Head :title="'ДТП '+accident.category+' по адресу '+accident.address"/>
    <div class="accident-page text-[#18334A]">
        <AccidentMapPan :accident="accident"/>

        <div class="relative max-w-[1140px] mx-auto px-4 z-10">
            <AccidentMainInfo :accident="accident"/>

            <section class="mt-8 flex flex-wrap">
                    <div v-for="road_condition in accident['road_conditions']" class="mt-2 bg-gray-200 px-2 py-1 rounded text-sm font-medium mr-2">{{road_condition}}</div>
                    <div v-for="weather in accident['weather']" class="mt-2 bg-gray-200 px-2 py-1 rounded text-sm font-medium mr-2">{{weather}}</div>
                    <div class="mt-2 bg-gray-200 px-2 py-1 rounded text-sm font-medium mr-2">{{accident['light']}}</div>
            </section>

            <section class="flex flex-wrap">
                    <div v-for="nearby in accident['nearby']" class="mt-2 bg-gray-200 px-2 py-1 rounded text-sm font-medium mr-2">{{nearby}}</div>
            </section>

            <section class="flex">
                    <div v-for="tag in accident['tags']" class="mt-2 bg-gray-200 px-2 py-1 rounded text-sm font-medium mr-2">{{tag}}</div>
            </section>

            <section class="participants mb-12">
                <h2 class="mt-10 text-2xl font-bold mb-3">Участники ДТП</h2>

                <div class="flex flex-wrap">
                    <ParticipantCard v-for="participant in accident.participants" :participant="participant"/>
                </div>

                <div v-for="vehicle in accident['vehicles']">
                    <h3 class="mt-4 text-xl font-bold mb-2">
                        {{ vehicle['brand'] ?? '--' }}, {{ vehicle['model'] ?? '--' }},
                        {{ vehicle['year'] ?? '--' }}, {{ vehicle['color'] ?? '--' }}
                    </h3>
                    <div class="flex flex-wrap">
                        <div v-for="participant in vehicle.participants.sort((a, b) => a.role > b.role)">
                            <ParticipantCard :participant="participant"/>
                        </div>
                    </div>
                </div>
            </section>

            <div class="w-full flex justify-center mb-20 border-b border-gray-200">
                <div class="flex items-center px-5 py-3 border border-gray-200 rounded space-x-2 mb-4">
                    <img src="/images/gibdd.svg" alt="">
                    <p class="font-bold">Официальные данные ГИБДД</p>
                </div>
            </div>
        </div>
    </div>
</template>
