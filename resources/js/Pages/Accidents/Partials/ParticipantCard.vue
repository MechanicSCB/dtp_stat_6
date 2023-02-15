<script setup>
let props = defineProps({
    participant: Object,
});

function getBgColorClass(participant) {
    let bgColorClass = participant['health_status'].startsWith('Скончался') ? ' bg-[#ff0000]' : participant['health_status'].startsWith('Не пострадал') ? ' bg-[#bbb]':' bg-[#ffc107]';

    return bgColorClass;
}

function getTextColorClass(participant) {
    let textColorClass = participant['health_status'].startsWith('Скончался') ? ' text-[#ff0000]' : participant['health_status'].startsWith('Не пострадал') ? ' text-[#000000]':' text-[#ffc107]';

    return textColorClass;
}
</script>

<template>
    <div class="card w-[300px] h-fit px-3 py-3 border rounded-lg mr-8 mb-3">
        <div class="flex items-center space-x-2">
            <i class="text-2xl text-white p-1 rounded-xl"
               :class="(participant['role'] === 'Водитель' ? 'icon-dtp-wheel' : 'icon-dtp-pedestrians')
               +(getBgColorClass(participant))
                "
            ></i>
            <div>
                <p class="font-bold">
                    {{ participant['role'] }}
                </p>
                <p class="text-sm">
                    {{ participant['gender'] }}
                    {{ participant['years_of_driving_experience'] ? ', стаж (лет) — ' + participant['years_of_driving_experience'] : '' }}
                </p>
            </div>
        </div>
        <div class="mt-2 backdrop-opacity-30 rounded p-1.5 text-sm font-medium bg-opacity-10"
             :class="getTextColorClass(participant) + getBgColorClass(participant)"
        >
            {{ participant['health_status'] }}
        </div>

        <div v-if="participant['violations'].length" class="mt-3 bg-gray-100 rounded p-1.5 text-sm font-medium pr-1">
            <div v-for="violation in participant['violations']" class="flex my-2 items-center space-x-2">
                <i class="material-symbols-outlined">error</i>
                <p>{{ violation }}</p>
            </div>
        </div>
    </div>
</template>

