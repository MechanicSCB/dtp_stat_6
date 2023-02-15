<?php

namespace App\Http\Controllers;

use App\Classes\AccidentFilter;
use App\Classes\CacheHandler;
use App\Classes\GeoCalc;
use App\Classes\TileHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HotspotLayerController extends Controller
{
    public string $cacheMode = CacheHandler::USE_CACHE;
    protected string $type = 'callback';
    protected CacheHandler $cacheHandler;
    private GeoCalc $geoCalc;

    public function __construct()
    {
        $this->cacheHandler = new CacheHandler();
        $this->geoCalc = new GeoCalc();
    }

    public function getData(string $req,int $z,int $x, int $y, Request $request): string
    {
        [$request['z'], $request['x'], $request['y']] = [$z, $x, $y];
        $tileClusters = $this->getTileClusters($request);
        $features = $this->getClustersFeatures($tileClusters);

        $data['data'] = [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];

        $dataJson = json_encode($data);
        $callback = "testCallback_tile_x_{$request['x']}_y_{$request['y']}_z_{$request['z']}" . '(' . $dataJson . ')';

        if ($this->cacheMode !== CacheHandler::IGNORE_CACHE) {
            $this->cacheHandler->store("$req/$z/{$x}_$y", $callback, $this->type);
        }

        return $callback;
    }

    private function getTileClusters(Request $request): Collection
    {
        $splitLevel = 4;
        $tile = $request->only(['x', 'y', 'z']);
        $zoom = $tile['z'] + $splitLevel;
        $tileQuadkey = $this->geoCalc->getQuadkeyFromTileNumber($tile);

        $accidents = DB::table(DB::raw('accidents'));

        $accidents->where('quadkey', 'like', "$tileQuadkey%");

        $accidents = (new AccidentFilter())->filter($accidents, $request->all());

        $accidents = $accidents
            ->selectRaw("count(id) as number_of_points, avg(latitude) as lat, avg(longitude) as lon, sum(dead_count) as dead_count, SUBSTR(quadkey, 1, $zoom) as quad")
            ->groupBy('quad')
            ->get();

        return $accidents;
    }

    public function getClustersFeatures(Collection $tileClusters): array
    {
        $features = [];

        foreach ($tileClusters as $cluster) {
            $quadTail = substr($cluster->quad, -4);
            $x = ($quadTail[0] % 2) * 128 + ($quadTail[1] % 2) * 64 + ($quadTail[2] % 2) * 32 + ($quadTail[3] % 2) * 16;
            $y = intval($quadTail[0] / 2) * 128 + intval($quadTail[1] / 2) * 64 + intval($quadTail[2] / 2) * 32 + intval($quadTail[3] / 2) * 16;
            $tileNumber = $this->geoCalc->getTileNumberFromQuadkey($cluster->quad);
            $features[] = [
                "type" => "Cluster",
                "properties" => [
                    "quad" => $cluster->quad,
                    "tileNumber" => $tileNumber,
                    "hintContent" => "<div>дтп: $cluster->number_of_points<br>погибших:$cluster->dead_count</div>",
                    "balloonContent" => 'download...',
                    "HotspotMetaData" => [
                        "id" => $cluster->quad,
                        "RenderedGeometry" => [
                            "type" => "Rectangle",
                            "coordinates" => [
                                [$x, $y],
                                [$x + 16, $y + 16],
                            ],
                        ],
                    ],
                ],
            ];
        }

        return $features;
    }
}
