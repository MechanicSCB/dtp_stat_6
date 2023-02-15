<script setup>
function toggleStreetLayer() {
    let streetLayer = document.querySelectorAll('[class*="ymaps-2"][class*="-ground-pane"]>ymaps')[2];

    if(streetLayer.offsetParent === null){
        streetLayer.style.display = 'block';
    }else{
        streetLayer.style.display = 'none';
    }
}
</script>

<template>
    <button class="mt-4 rounded bg-gray-200 py-1 px-2" @click.prevent="toggleStreetLayer">Слой улиц</button>
</template>
