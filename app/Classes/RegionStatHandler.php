<?php


namespace App\Classes;


use App\Models\Accident;
use App\Models\Region;
use App\Models\Subregion;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RegionStatHandler
{
    private array $regions;
    private array $subregions;

    public function __construct()
    {
        $this->regions = Cache::rememberForever('statRegions', fn() => Region::query()->pluck('name','id')->toArray());
        $this->subregions = Cache::rememberForever('statSubregions', fn() => Subregion::query()->pluck('name','id')->toArray());
    }

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
                $accidents = $accidents->get(['region_id', 'subregion_id'])->toArray();

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
        $subregionId = @$filter['subregion_id'];
        $accidents = DB::table('accidents');
        $accidents = (new AccidentFilter())->filter($accidents, $filter);
        $accidents->where('region_id', $regionId);

        if ($subregionId) {
            $stat['subregion_name'] = $this->subregions[$subregionId];
            $accidents->where('subregion_id', $subregionId);
        }

        $stat['region_id'] = $regionId;
        $stat['region_name'] = $this->regions[$regionId];
        $stat['accident_count'] = $accidents->count();
        $stat['dead_count'] = $accidents->sum('dead_count');
        $stat['injured_count'] = $accidents->sum('injured_count');

        return $stat;
    }
}
