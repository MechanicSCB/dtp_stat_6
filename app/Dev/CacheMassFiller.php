<?php


namespace App\Dev;


use App\Classes\CacheHandler;
use App\Classes\TileHandler;
use App\Http\Controllers\HotspotLayerController;
use App\Http\Controllers\ImageLayerController;
use App\Models\Accident;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CacheMassFiller
{
    protected int $threshold = 10000; // min number of tiles, than continue recursive child filling
    protected Request $filterRequest;
    protected string $filterKey;

    public function fill()
    {
        //df(tmr(@$this->start), 'fill the cache');
        set_time_limit(300);
        $quadkey = '1203'; //$quadkey = '120111201300033311330002';
        $filters = $this->getFilters();
        $filters = [[]];
        df(tmr(@$this->start), $filters);

        foreach ($filters as $filter){
            // make request from filter
            $this->filterRequest = $this->getFilterRequest($filter);
            $this->filterKey = $this->getFilterKey();

            // fill the cache with filter key
            $this->fillTileChildren($quadkey);
        }

        df(tmr(@$this->start), 'done');
    }

    protected function getFilters():array
    {
        //$example_filter = ['period' => '2020', 'participant_categories' => ['2', '3'], 'severities' => ['2', '3']];

        $filters = [[]];
        $years = range(2015, 2022);

        foreach ($years as $year) {
            $filters[] = [
                'period' => $year,
            ];
        }

        return $filters;
    }

    protected function getFilterRequest(array $filter = []): Request
    {
        $filterRequest = new Request();
        $filterRequest->replace($filter);

        return $filterRequest;
    }

    public function fillTileChildren(string $quadkey): ?int
    {
        if (strlen($quadkey) > 14) {
            return null;
        }

        $pointCount = $this->fillTile($quadkey);

        if ($pointCount < $this->threshold) {
            return $pointCount;
        }

        for ($i = 0; $i <= 3; $i++) {
            $this->fillTileChildren($quadkey . $i);
        }

        return $pointCount;
    }

    public function fillTile(string $quadkey): int
    {
        $accidentsCount = Accident::where('quadkey', 'like', "$quadkey%")->count();
        $tile = (new TileHandler())->getTileNumberFromQuadkey($quadkey);
        $this->fillTileImage($tile['z'], $tile['x'], $tile['y']);
        $this->fillTileHotspot($tile['z'], $tile['x'], $tile['y']);

        return $accidentsCount;
    }

    public function fillTileImage(string $z, string $x, string $y): void
    {
        $imgController = new ImageLayerController();
        $imgController->cacheMode = CacheHandler::USE_CACHE;
        $imgController->returnImg = false;
        $imgController->getTileImage($this->filterKey, $z, $x, $y, $this->filterRequest);
    }

    public function fillTileHotspot(string $z, string $x, string $y): void
    {
        $hotspotController = new HotspotLayerController();
        $hotspotController->cacheMode = CacheHandler::USE_CACHE;
        $hotspotController->getData($this->filterKey, $z, $x, $y, $this->filterRequest);
    }

    public function getFilterKey(): string
    {
        $url = Arr::query($this->filterRequest->all());
        $cmd = "node js/getFilterCacheKey.js '$url' 2&>1";

        return trim(shell_exec($cmd));
    }
}
