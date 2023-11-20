<?php


namespace App\Classes;


use GdImage;

class TileWebpGenerator
{
    public function generateTileImg(array $elements, int $markerSize, int $tileSize = null): GdImage|bool
    {
        $tileSize ??= config('map.image_layer_tile_size');
        $tileImage = imagecreatetruecolor($tileSize, $tileSize);
        $bgColor = imagecolorallocatealpha($tileImage, 0, 0, 0, 127);

        $pointImageSize = $markerSize * 2;
        $pointImgSev[1] = imagecreatefrompng(storage_path("app/accident-markers/png/circle-shadow/circle-FACC15-$markerSize.png"));
        $pointImgSev[2] = imagecreatefrompng(storage_path("app/accident-markers/png/circle-shadow/circle-F97316-$markerSize.png"));
        $pointImgSev[3] = imagecreatefrompng(storage_path("app/accident-markers/png/circle-shadow/circle-DC2626-$markerSize.png"));

        imagefill($tileImage, 0, 0, $bgColor);

        foreach ($elements as $element) {
            $elemX = $element['tileCoords']['x'] - $pointImageSize/2;
            $elemY = $element['tileCoords']['y'] - $pointImageSize/2;
            $severity = $element['props']['severity_id'];

            imagecopy($tileImage, $pointImgSev[$severity], $elemX, $elemY, 0, 0, $pointImageSize, $pointImageSize);
        }

        imagesavealpha($tileImage, true);

        return $tileImage;
    }
}
