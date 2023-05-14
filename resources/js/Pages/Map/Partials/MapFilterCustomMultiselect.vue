<script setup>
// TODO Fix filter after reload selected options disappearing
import {useForm} from "@inertiajs/vue3";
import {filter} from "../../../Stores/filterStore.js"
import {ref} from "vue";
import CheckIcon from "../../../Svg/CheckIcon.vue";
import BackArrowIcon from "../../../Svg/BackArrowIcon.vue";
import CloseIcon from "../../../Svg/CloseIcon.vue";

let props = defineProps({
    name: String,
    field: String,
    options: Object,
})

let isOpen = ref(false);
let search = ref('');
const emit = defineEmits();

function submit() {
    emit('submit')
    search.value = '';
}

function clearSelect() {
    filter.form[props.field] = [];
    submit();
}

function filteredOptions() {
    return props.options.filter(v => v.name.toLowerCase().includes(search.value.toLowerCase()))
        .sort((a, b) => filter.form[props.field].includes(b.id) - filter.form[props.field].includes(a.id));
}
</script>

<template>
    <!--  Select Button  -->
    <div class="mt-2 flex items-center">
        <button @click.prevent="isOpen = !isOpen" class="px-2 h-7 rounded text-xs hover:bg-[#8d99a5] hover:text-white"
                :class="filter.form[field].length?'bg-[#586c7c] text-white rounded-r-none':'bg-[#f4f8fa] text-[#18334a]'">
            {{ name }}
        </button>
        <button v-if="filter.form[field].length" @click="clearSelect"
                class="h-7 px-2 text-sm rounded-r bg-[#586c7c] text-gray-300 hover:bg-[#8d99a5]">
            <CloseIcon class="w-4 fill-[#ccd2d7]"/>
        </button>
    </div>

    <!--  Select List  -->
    <div v-if="isOpen" class="absolute z-[100] left-0 top-0 flex flex-col h-full w-full px-4 bg-white">
        <button @click="isOpen = !isOpen"
                class="w-fit mt-2 px-2 py-1 rounded text-sm text-[#18334a] hover:shadow-[0_0_0_1px_#ccd2d7] rounded-full py-0.5 mb-3"
        >
            <BackArrowIcon class="inline w-4 h-4"/>
            {{ name }}
        </button>

        <input v-model="search" type="text" class="border-none bg-[#f4f8fa] text-[#18334a] rounded !ring-black">

        <div class="mt-4 flex flex-col overflow-y-auto text-xs gap-3 pb-5" style="scrollbar-width: thin;">
            <label class="flex cursor-pointer" v-for="option in filteredOptions()">
                <input @change="submit" v-model="filter.form[field]" :value="option.id" type="checkbox" class="hidden">
                <div class="flex-none w-[18px] h-[18px] border flex items-center justify-center rounded mr-2"
                     :class="{'bg-[#18334a]':checked = filter.form[field].includes(option.id)}">
                    <CheckIcon v-show="checked" class="m-0.5 w-full h-full fill-white"/>
                </div>
                {{ option.name }}
            </label>
        </div>
    </div>
</template>
<style>
label:hover div {
    background-color: #8d99a5;
}
</style>
