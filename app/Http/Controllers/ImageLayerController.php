<?php

namespace App\Http\Controllers;

use App\Classes\CacheHandler;
use App\Classes\TilePngGenerator;
use App\Classes\TileHandler;
use App\Classes\TileSvgGenerator;
use App\Classes\TileWebpGenerator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageLayerController extends Controller
{
    public string $cacheMode = CacheHandler::USE_CACHE;
    public bool $returnImg = true; // true -> return image; false -> return null (for cache filler)
    protected string $imageType = 'webp';
    protected TileHandler $tileHandler;
    protected CacheHandler $cacheHandler;
    private TilePngGenerator $imageCreator;

    public function __construct()
    {
        $this->cacheHandler = new CacheHandler();
        $this->tileHandler = new TileHandler();
        $this->imageCreator = new TilePngGenerator();
    }

    public function getTileImage(string $filterKey, int $z, int $x, int $y, Request $request): BinaryFileResponse|null
    {
        // dd(tmr(),77);
        [$request['z'], $request['x'], $request['y']] = [$z, $x, $y];
        $tileAccidents = $this->tileHandler->getTileAccidents($request);
        $tileNumber = $request->only('x', 'y', 'z');
        $tileElements = $this->tileHandler->getTileImageElements($tileAccidents, $tileNumber);
        $markerSize = config('map.tile_marker_sizes')[$tileNumber['z']] ?? 5;

        if ($this->imageType === 'png') {
            $tileImage = $this->imageCreator->generateTileImg($tileElements, $markerSize);
        } elseif ($this->imageType === 'webp') {
            $tileImage = (new TileWebpGenerator())->generateTileImg($tileElements, $markerSize);
        } elseif ($this->imageType === 'svg') {
            $tileImage = (new TileSvgGenerator())->generateTileImg($tileElements, $markerSize);
        }

        if ($this->cacheMode !== CacheHandler::IGNORE_CACHE) {
            $this->cacheHandler->store("$filterKey/$z/{$x}_$y", $tileImage, $this->imageType);
        }

        if($this->returnImg === false){
            return null;
        }

        // return image and die()
        if ($this->imageType === 'png') {
            header("Content-type: image/png");

            if (class_basename($tileImage) === 'GdImage') {
                imagepng($tileImage); // show in browser
            } else {
                return response()->file($tileImage);
            }
        } elseif ($this->imageType === 'webp') {
            header("Content-type: image/webp");

            if (class_basename($tileImage) === 'GdImage') {
                imagewebp($tileImage); // show in browser
            } else {
                return response()->file($tileImage);
            }
        } elseif ($this->imageType === 'svg') {
            header('Content-Type: image/svg+xml');
            echo $tileImage;
        }

        die();
    }

}
