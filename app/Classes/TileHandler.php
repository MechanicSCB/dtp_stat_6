<?php


namespace App\Classes;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;

class TileHandler
{
    public int $tileSize;
    private GeoCalc $geoCalc;

    public function __construct()
    {
        $this->tileSize = config('map.image_layer_tile_size');
        $this->geoCalc = new GeoCalc($this->tileSize);
    }

    public function getTileAccidents(Request $request, $margin = null): array
    {
        $tile = $request->only(['x', 'y', 'z']);
        $bbox = $this->geoCalc->getBboxFromTileNumber($tile);

        if ($tile['z'] < 8) {
            $margin ??= 0.1;
        } elseif ($tile['z'] < 12) {
            $margin ??= 0.02;
        } else {
            $margin ??= 0.002; // z = 15
        }

        if ($margin) {
            $fields = ['id', 'latitude', 'longitude', 'severity_id'];
        } else {
            $fields = ['accidents.id', 'latitude', 'longitude', 'severity_id', 'datetime', 'info'];
        }

        $idx = ''; //$idx = 'force index(accidents_longitude_index)';
        $accidents = DB::table(DB::raw('accidents ' . $idx));

        // SELECT
        $accidents->select($fields);

        // lonlat1 filter
        $lon1Range = range(truncate($bbox['long0'] - $margin, 1) * 10, truncate($bbox['long1'] + $margin, 1) * 10);
        $lat1Range = range(truncate($bbox['lat0'] - $margin, 1) * 10, truncate($bbox['lat1'] + $margin, 1) * 10);

        $lonlats1 = [];

        foreach ($lon1Range as $lon1) {
            foreach ($lat1Range as $lat1) {
                $lonlats1[] = number_format($lon1 / 10, 1) . '-' . number_format($lat1 / 10, 1);
            }
        }

        $accidents->whereIn('lonlat1', $lonlats1);

        $accidents->where('latitude', '>=', $bbox['lat0'] - $margin);
        $accidents->where('latitude', '<', $bbox['lat1'] + $margin);
        $accidents->where('longitude', '>=', $bbox['long0'] - $margin);
        $accidents->where('longitude', '<', $bbox['long1'] + $margin);

        $accidents = (new AccidentFilter())->filter($accidents, $request->all());

        if ($margin > 0) {
            $accidents->orderBy('severity_id');
        } else {
            $accidents->orderByDesc('severity_id');
            $accidents->orderByDesc('datetime');
        }

        $accidents = $accidents->get();

        return stdToArray($accidents);
    }

    #[Pure]
    public function getTileImageElements(array $tileAccidents, array $tileNumber): array
    {
        $tileElements = [];

        foreach ($tileAccidents as $tileAccident) {
            $pointGeoCoords = ['latitude' => $tileAccident['latitude'], 'longitude' => $tileAccident['longitude']];
            $elem['tileCoords'] = $this->geoCalc->getPointTileCoordsFromGeoCoords($pointGeoCoords, $tileNumber);
            $elem['props'] = $tileAccident;

            $tileElements[] = $elem;
        }

        return $tileElements;
    }

}
