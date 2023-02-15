import {filter} from "./filterStore.js"
import {ref} from "vue";

export let regionStat = {};

regionStat = {
    region_name: null,
    subregion_name: null,
    accident_count: null,
    injured_count: null,
    dead_count: null,
};

let centerMapRegionId = ref(null);
let centerMapSubRegionName = ref(null);

export function getStat(mainMap) {
    let bounds = mainMap.getBounds();
    filter.refresh();

    fetch('/get-stat?bounds[0]=' + bounds[0] + '&bounds[1]=' + bounds[1] + '&region_id=' + centerMapRegionId.value + '&subregion_name=' + centerMapSubRegionName.value + '&' + filter.queryString).then(function (response) {
        response.text().then(function (stat) {
            if (stat) {
                stat = JSON.parse(stat);
                centerMapRegionId.value = stat['region_id'];
                centerMapSubRegionName.value = stat['subregion_name'] ?? '--';
                document.querySelectorAll('#map-stat-region-name')[0].innerHTML = stat['region_name'];
                document.querySelectorAll('#map-stat-subregion-name')[0].innerHTML = centerMapSubRegionName.value;
                document.querySelectorAll('#map-stat-accident-count')[0].innerHTML = stat['accident_count'];
                document.querySelectorAll('#map-stat-injured-count')[0].innerHTML = stat['injured_count'];
                document.querySelectorAll('#map-stat-dead-count')[0].innerHTML = stat['dead_count'];
            }
        });
    });
};

