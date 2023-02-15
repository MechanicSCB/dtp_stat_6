<?php


namespace App\Classes;


use App\Models\Accident;
use App\Models\Region;
use Illuminate\Support\Facades\DB;

class RegionStatHandler
{
    public function getSubregionMode(?string $zoom): bool
    {
        $zoom = intval($zoom);

        if ($zoom === 0) {
            $zoom = 12;
        }

        if ($zoom > 12) {
            return true;
        }

        return false;
    }

    public function getCenterMapRegionSubRegion(): ?string
    {
        $bounds = request('bounds');
        $bounds = array_map(fn($v) => explode(',', $v, 2), $bounds);
        $cLat = ($bounds[1][0] + $bounds[0][0]) / 2;
        $cLong = ($bounds[1][1] + $bounds[0][1]) / 2;
        $geoCalc = new GeoCalc();

        $quadkey = $geoCalc->getQuadkeyFromGeoCoords($cLat, $cLong, 18);

        do {
            if (($accidents = Accident::query()->where('quadkey', 'like', "$quadkey%"))->count() > 5) {
                $accidents = $accidents->get(['region_id', 'info->region'])->toArray();

                $res = [];

                foreach ($accidents as $accident) {
                    $regionId = head($accident);
                    $subregion = last($accident);
                    $res["$regionId|$subregion"][] = 1;
                }

                $res = array_map(fn($v) => count($v), $res);
                arsort($res);

                return array_key_first($res);
            }

            $quadkey = substr($quadkey, 0, -1);
        } while (strlen($quadkey) > 10);

        return null;
    }

    public function getStat(array $filter): array
    {
        $regionId = $filter['region_id'];
        $subregion = @$filter['subregion_name'];
        $accidents = DB::table(DB::raw('accidents'));
        $accidents = (new AccidentFilter())->filter($accidents, $filter);
        $accidents->where('region_id', $regionId);

        if ($subregion) {
            $stat['subregion_name'] = $subregion;
            $accidents->where('subregion', $subregion);
        }

        $stat['region_id'] = $regionId;
        $stat['region_name'] = Region::query()->find($regionId)['name'];
        $stat['accident_count'] = $accidents->count();
        $stat['dead_count'] = $accidents->sum('dead_count');
        $stat['injured_count'] = $accidents->sum('injured_count');

        return $stat;
    }
}
