<script setup>

</script>

<template>
    <div id="map-stat" class="absolute hidden md:flex md: left-[430px] mt-4 rounded-lg bg-white h-[50px] z-50 shadow-md
    p-2 text-sm space-x-6">
        <div class="flex flex-col">
            <div id="map-stat-region-name" class="font-semibold">Регион</div>
            <div id="map-stat-subregion-name" class="text-xs">Район</div>
        </div>
        <div class="flex flex-col">
            <span class="text-gray-500 text-xs">ДТП</span>
            <span id="map-stat-accident-count" class="font-semibold">-</span>
        </div>
        <div class="flex flex-col">
            <span class="text-gray-500 text-xs">Пострадали</span>
            <span id="map-stat-injured-count" class="font-semibold text-[#FACC15]">-</span>
        </div>
        <div class="flex flex-col">
            <span class="text-gray-500 text-xs">Погибли</span>
            <span id="map-stat-dead-count" class="font-semibold text-[#DC2626]">-</span>
        </div>
    </div>
</template>

