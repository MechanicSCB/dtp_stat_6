import {useForm} from "@inertiajs/vue3";
import {getFilterCacheKey} from "../Functions/getFilterCacheKey.js";
import {objectToQueryString} from "../Functions/objectToQueryString.js";

export let filter = {};

filter.form = useForm({
    period: null,
    participant_categories: [],
    severities: [],
    accident_categories: [],
    light_conditions: [],
    zoom: null,
    lat: null,
    lon: null,
});

filter.refresh = function (){
    filter.queryString = objectToQueryString(filter.form.data());
    filter.key = getFilterCacheKey(filter.queryString);
    window.history.replaceState({}, '', `${location.pathname}?${filter.queryString}`);
}
