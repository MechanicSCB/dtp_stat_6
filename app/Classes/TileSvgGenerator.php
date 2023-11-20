<?php


namespace App\Classes;


use GdImage;

class TileSvgGenerator
{
    public function generateTileImg(array $elements, int $markerSize, int $tileSize = null)
    {
        $tileSize ??= config('map.image_layer_tile_size');
        $tileImage = '<svg width="256" height="256" viewBox="0 0 256 256" version="1.1" xmlns="http://www.w3.org/2000/svg">';
        $colors = ['','FACC15','F97316','DC2626'];
        $markerSize+=2;
        $markerSize/=2;

        foreach ($elements as $element) {
            $elemX = $element['tileCoords']['x'];
            $elemY = $element['tileCoords']['y'];
            $severityId = $element['props']['severity_id'];

            $tileImage .= "<circle cx='$elemX' fill='#$colors[$severityId]' stroke='#000' stroke-opacity='0.5' cy='$elemY' r='$markerSize'/>";
        }

        $tileImage .= '</svg>';

        return $tileImage;
    }
}
