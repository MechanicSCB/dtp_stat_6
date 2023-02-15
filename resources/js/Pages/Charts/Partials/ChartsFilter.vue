<script setup>
import Multiselect from 'vue-multiselect'
import {useForm} from "@inertiajs/vue3";

let props = defineProps({
    filterFormData: Object,
});

let form = useForm({
    years: [],
    severities: [],
    categories: [],
    light_conditions: [],
    regions: [],
    subregions: [],
});

function submit() {
    form.get('/charts', {preserveScroll: true, preserveState: true})
}

function selectRegion(region_id) {
    form.subregions = [];
    form.regions = [region_id];
}

function regionRemove(region_id) {
    form.subregions = form.subregions.filter(sub => !props.filterFormData['allSubregions'].filter(i=>i.region_id===region_id).map(v=>v.subregion).includes(sub))
    submit();
}
</script>

<template>
    <div class="mt-6 flex text-xs mb-10 flex flex-wrap gap-x-2 gap-y-2">
        <!-- years -->
        <div class="w-[130px]">
            <multiselect
                @close="submit"
                @remove="submit"
                v-model="form.years"
                :options="filterFormData['allYears']"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="true"
                :hideSelected="true"
                :searchable="false"
                placeholder="Все годы"
                selectLabel=""
                deselectLabel=""
                selectedLabel=""

                class="!text-xs"
            >
                <template slot="selection" slot-scope="{ values, search, isOpen }">
                <span class="multiselect__single"
                      v-if="values.length"
                >
                    {{ values.length }} options selected
                </span>
                </template>
            </multiselect>
        </div>
        <!-- severities -->
        <div class="w-[130px]">
            <multiselect
                @close="submit"
                @remove="submit"
                v-model="form.severities"
                :options="[1,2,3]"
                :custom-label="opt => ['Легкий','Тяжёлый','С погибшими'][opt-1]"
                :multiple="true"
                :close-on-select="true"
                :clear-on-select="true"
                :hideSelected="true"
                :searchable="false"
                placeholder="Вред здоровью"
                selectLabel=""
                deselectLabel=""
                selectedLabel=""

                class="!text-xs"
            >
                <template slot="selection" slot-scope="{ values, search, isOpen }">
                <span class="multiselect__single"
                      v-if="values.length"
                      v-show="!isOpen"
                >
                    {{ values.length }} options selected
                </span>
                </template>
            </multiselect>
        </div>
        <!-- categories -->
        <div class="w-[180px]">
            <multiselect
                @close="submit"
                @remove="submit"
                v-model="form.categories"
                :options="filterFormData['allCategories']"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                :hideSelected="true"
                placeholder="Тип ДТП"
                selectLabel=""
                deselectLabel=""
                selectedLabel=""

                class="!text-xs"

            >
            </multiselect>
        </div>
        <!-- light_conditions -->
        <div class="w-[180px]">
            <multiselect
                @close="submit"
                @remove="submit"
                v-model="form.light_conditions"
                :options="filterFormData['allLightConditions']"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                :hideSelected="true"
                placeholder="Освещение"
                selectLabel=""
                deselectLabel=""
                selectedLabel=""

                class="!text-xs"

            >
            </multiselect>
        </div>
        <!-- regions -->
        <div class="w-[200px]">
            <multiselect
                @close="submit"
                @remove="regionRemove"
                @input="inputRegion"
                @select="selectRegion"
                v-model="form.regions"
                :multiple="true"
                :options="filterFormData['allRegions'].map(region => region.id)"
                :custom-label="opt => filterFormData['allRegions'].find(x => x.id == opt).name"
                :close-on-select="true"
                :clear-on-select="true"
                :preserve-search="true"
                :hideSelected="true"
                placeholder="Регионы"
                selectLabel=""
                deselectLabel=""
                selectedLabel=""

                class="!text-xs"

            >
            </multiselect>
        </div>
        <!-- subregions -->
        <div v-if="form.regions.length" class="w-[200px]">
            <multiselect
                @close="submit"
                @remove="submit"
                v-model="form.subregions"
                :options="filterFormData['allSubregions'].filter(i => form.regions.includes(i.region_id)).map(v => v.subregion)"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="true"
                :preserve-search="true"
                :hideSelected="true"
                placeholder="Районы"

                selectLabel=""
                deselectLabel=""
                selectedLabel=""

                class="!text-xs"
            >
            </multiselect>
        </div>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style>
.multiselect__tags {
    font-size: 12px;
    font-weight: bold;
}
.multiselect__tag {
    background-color: #aaa;
}
.multiselect__tag-icon::after {
    content: "×";
    color: #777;
    font-size: 14px;
}
.multiselect__content-wrapper {
    scrollbar-width: thin;
}
.multiselect__input:focus {
    --tw-ring-color: #ccc;
}
</style>
