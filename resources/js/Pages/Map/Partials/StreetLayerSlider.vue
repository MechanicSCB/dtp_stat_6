<script setup>
import {ref} from "vue";

let opacity = ref(0);

function toggleStreetLayer() {
    document.querySelectorAll('[class*="ymaps-2"][class*="-ground-pane"]>ymaps')[2]
        .style.opacity = opacity.value/100;
}
</script>

<template>
    <div class="slider">
        <input type="range" min="0" max="100" v-model="opacity" @input.prevent="toggleStreetLayer">
        <p>Слой улиц: {{opacity}}</p>
    </div>
</template>
