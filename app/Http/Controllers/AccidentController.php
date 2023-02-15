<?php

namespace App\Http\Controllers;

use App\Classes\RegionStatHandler;
use App\Models\Accident;
use Illuminate\Support\Facades\Cache;
use Inertia\Response;
use Inertia\ResponseFactory;

class AccidentController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $years = range(2022, 2015);
        $severities = config('map.severities');
        $participant_categories = config('map.participantCategories');
        $accident_categories = config('map.accidentCategories');
        $weather_conditions = config('map.weatherConditions');
        $light_conditions = config('map.lightConditions');

        return inertia('Map/Index', compact(
            'years', 'severities', 'participant_categories',
            'accident_categories','weather_conditions', 'light_conditions'
        ));
    }

    public function show(Accident $accident): Response|ResponseFactory
    {
        $accidentInfo = json_decode($accident['info'], 1);
        $accidentInfo['datetime_string'] = $accident['datetime_string'];

        return inertia('Accidents/Show', ['accident' => $accidentInfo]);
    }

    public function getRegionStat(): array
    {
        $regionStatHandler = new RegionStatHandler();
        $subregionMode = $regionStatHandler->getSubregionMode(request('zoom'));
        $regionSubRegion = $regionStatHandler->getCenterMapRegionSubRegion(); // 77|Таганский

        if ($regionSubRegion === null) {
            die();
        }

        [$regionId, $subregionName] = explode('|', $regionSubRegion, 2);

        $filter = request()->except(['bounds', 'lat', 'lon', 'region_id', 'zoom', 'subregion_name']);
        //$filter = request()->only(['period', 'participant_categories','severities','accident_categories','light_conditions]);

        $filter['region_id'] = $regionId;

        if ($subregionMode) {
            $filter['subregion_name'] = $subregionName;
        }

        //Cache::flush();
        $regionStat = Cache::rememberForever(json_encode($filter), fn() => $regionStatHandler->getStat($filter));

        return $regionStat;
    }
}
