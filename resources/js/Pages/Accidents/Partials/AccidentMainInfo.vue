<script setup>
let props = defineProps({
    accident: Object,
});
console.log()
</script>

<template>
    <div class="flex mt-2">
        <div class="hidden mt-[-80px] md:flex flex-none items-center py-5 px-1 h-fit  bg-white rounded-lg
        shadow-[0_0_10px_2px_rgba(200,200,200,1)] mr-8">
            <img class="max-w-[350px]" alt="accident scheme" :src="'/storage/img/schemes/'+accident.info.scheme+'.png'">
        </div>

        <div class="w-full">
            <p>{{ accident['datetime_string']}}
                <span class="text-gray-300">| </span>
                <span :class="{'text-gray-300':!accident.info.address}">{{ accident.info.address ?? 'Адрес неизвестен' }}</span>
            </p>

            <h1 class="my-8 leading-7 text-[30px] font-bold">{{ accident.accident_category.name ?? '--' }}</h1>

            <div class="flex flex-wrap gap-y-3">
                <div class="flex items-center">
                    <i class=" text-2xl text-[rgba(24,51,74,0.5)] icon-dtp-auto"></i>
                    <div class="mx-5 leading-5">
                        <p class="text-[rgba(24,51,74,0.5)]">Транспорт</p>
                        <p class="font-bold">{{ accident.info.vehicles.length }} </p>
                    </div>
                </div>
                <div class="flex items-center">
                    <i class=" text-2xl text-[rgba(24,51,74,0.5)] icon-dtp-participants"></i>
                    <div class="mx-5 leading-5">
                        <p class="text-[rgba(24,51,74,0.5)]">Участники</p>
                        <p class="font-bold">{{ accident['participants_count'] }}</p>
                    </div>
                </div>
                <div class="flex items-center text-[#FACC15]">
                    <i class="text-2xl icon-dtp-injured"></i>
                    <div class="mx-5 leading-5">
                        <p class="text-[rgba(24,51,74,0.5)]">Пострадали</p>
                        <p class="font-bold">{{ accident['injured_count'] }}</p>
                    </div>
                </div>
                <div class="flex items-center text-[#DC2626]">
                    <i class="text-2xl icon-dtp-dead"></i>
                    <div class="mx-5 leading-5">
                        <p class="text-[rgba(24,51,74,0.5)]">Погибли</p>
                        <p class="font-bold">{{ accident['dead_count'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

