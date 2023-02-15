<?php


namespace App\Classes;


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class GeoCalc
{
    public function __construct(public ?int $tileSize = null)
    {
        $this->tileSize ??= config('map.image_layer_tile_size');
    }

    #[ArrayShape(['x' => "float|int", 'y' => "float|int"])]
    public function fromGeoToPixels(int $z, float $lat, float $long):array
    {
        $rho = pow(2, $z + 8) / 2;
        $beta = $lat * pi() / 180;
        $e = 0.0818191908426; // wsg84 (ellipse) --- $e = 0;  (spherical)
        $phi = (1 - $e * sin($beta)) / (1 + $e * sin($beta));
        $theta = tan(pi() / 4 + $beta / 2) * pow($phi, $e / 2);
        $x_p = $rho * (1 + $long / 180);
        $y_p = $rho * (1 - log($theta) / pi());

        return ['x' => $x_p, 'y' => $y_p];
    }

    public function getPointTileCoordsFromPixelCoords(array $tile, array $pointPixelCoords): array
    {
        $pointTileCoords = [];

        foreach ($pointPixelCoords as $axis => $pixelCoord) {
            $pointTileCoords[$axis] = round($pixelCoord - $tile[$axis] * 256, 0);
        }

        return $pointTileCoords;
    }

    #[Pure]
    public function getQuadkeyFromTileNumber(array $tileNumber, int $tileSize = null): string
    {
        $tileSize ??= $this->tileSize;
        $tileX = $tileNumber['x'] * $tileSize / 256;
        $tileY = $tileNumber['y'] * $tileSize / 256;

        return $this->tileXYToQuadKey($tileX, $tileY, $tileNumber['z']);
    }

    public function tileXYToQuadKey(int $tileX, int $tileY, int $zoom): string
    {
        if ($zoom == 0) {
            return "0";
        }

        $quadKey = "";

        for ($i = $zoom; $i > 0; $i--) {
            $digit = 0;
            $mask = 1 << ($i - 1);
            if (($tileX & $mask) != 0) {
                $digit++;
            }
            if (($tileY & $mask) != 0) {
                $digit++;
                $digit++;
            }
            $quadKey .= $digit;
        }

        return $quadKey;
    }

    #[ArrayShape(['x' => "int", 'y' => "int", 'z' => "int"])]
    public function getTileNumberFromQuadkey(string $quadKey): array
    {
        $tileX = $tileY = 0;
        $zoom = strlen($quadKey);

        for ($i = $zoom; $i > 0; $i--) {
            $mask = 1 << ($i - 1);
            switch ($quadKey[$zoom - $i]) {
                case '0':
                    break;

                case '1':
                    $tileX |= $mask;
                    break;

                case '2':
                    $tileY |= $mask;
                    break;

                case '3':
                    $tileX |= $mask;
                    $tileY |= $mask;
                    break;

                default:
            }
        }

        return ['x' => $tileX, 'y' => $tileY, 'z' => $zoom];
    }

    #[Pure] #[ArrayShape(['lat0' => "float", 'long0' => "float", 'lat1' => "float", 'long1' => "float"])]
    public function getBboxFromTileNumber(array $tileNumber, int $tileSize = 256): array
    {
        $zoom = $tileNumber['z'];
        $tilePixelCoords['x'] = $tileNumber['x'] * $tileSize;
        $tilePixelCoords['y'] = $tileNumber['y'] * $tileSize * pow(2, 17 - $zoom);

        return $this->getTileBboxFromPixelCoords($tilePixelCoords, $zoom, $tileSize);
    }

    #[ArrayShape(['lat0' => "float", 'long0' => "float", 'lat1' => "float", 'long1' => "float"])]
    public function getBboxFromQuadkey(string $quadkey): array
    {
        $tileNumber = $this->getTileNumberFromQuadkey($quadkey);

        return $this->getBboxFromTileNumber($tileNumber);
    }

    #[Pure] #[ArrayShape(['lat0' => "float", 'long0' => "float", 'lat1' => "float", 'long1' => "float"])]
    public function getTileBboxFromPixelCoords(array $tilePixelCoords, int $zoom, int $tileSize = 256): array
    {
        $lat0 = $this->pixelYtoLatZoom17($tilePixelCoords['y'] + $tileSize * pow(2, 17 - $zoom));
        $lat1 = $this->pixelYtoLatZoom17($tilePixelCoords['y']);
        $long0 = $this->pixelXtoLong($tilePixelCoords['x'], $zoom);
        $long1 = $this->pixelXtoLong($tilePixelCoords['x'] + $tileSize, $zoom);

        return ['lat0' => $lat0, 'long0' => $long0, 'lat1' => $lat1, 'long1' => $long1];
    }

    public function pixelXtoLong($pxX, $z): float
    {
        $rho = pow(2, $z + 8) / 2;
        $long = ($pxX - $rho) * 180 / $rho;

        return $long;
    }

    public function pixelYtoLatZoom17($pxY): float
    {
        $g = (pi() / 2) - 2 * atan(1 / exp((20037508.342789 - ($pxY * 64) / 53.5865938) / 6378137));
        $lat = 180 / pi() * ($g + 0.00335655146887969 * Sin(2 * $g) + 0.00000657187271079536 * Sin(4 * $g) + 0.00000001764564338702 * Sin(6 * $g) + 0.00000000005328478445 * Sin(8 * $g));

        return $lat;
    }

    #[Pure] #[ArrayShape(['x' => "int", 'y' => "int"])]
    public function getPointTileCoordsFromGeoCoords(array $pointGeoCoords, array $tileNumber, int $tileSize = null): array
    {
        $tileSize ??= $this->tileSize;
        $pointPixelCoords = $this->fromGeoToPixels($tileNumber['z'], $pointGeoCoords['latitude'], $pointGeoCoords['longitude']);
        $pointX = (int)$pointPixelCoords['x'] - $tileNumber['x'] * $tileSize;
        $pointY = (int)$pointPixelCoords['y'] - $tileNumber['y'] * $tileSize;

        return ['x' => $pointX, 'y' => $pointY];
    }

    #[Pure]
    public function getQuadkeyFromGeoCoords(?float $lat, ?float $long, int $zoom = 24): string
    {
        if (! $lat || ! $long) {
            return 0;
        }

        $pixelCoords = $this->fromGeoToPixels($zoom, $lat, $long);
        $tileX = $pixelCoords['x'] / 256;
        $tileY = $pixelCoords['y'] / 256;
        $quadkey = $this->tileXYToQuadKey($tileX, $tileY, $zoom);

        if ($quadkey < 0) {
            $quadkey = 0;
        }

        return $quadkey;
    }

    //public function quadKeyToTileXYFromWeb($quadKey, &$tileX, &$tileY, &$levelOfDetail)
    //{
    //    $tileX = $tileY = 0;
    //    $levelOfDetail = strlen($quadKey);
    //
    //    for ($i = $levelOfDetail; $i > 0; $i--) {
    //        $mask = 1 << ($i - 1);
    //        switch ($quadKey[$levelOfDetail - $i]) {
    //            case '0':
    //                break;
    //
    //            case '1':
    //                $tileX |= $mask;
    //                break;
    //
    //            case '2':
    //                $tileY |= $mask;
    //                break;
    //
    //            case '3':
    //                $tileX |= $mask;
    //                $tileY |= $mask;
    //                break;
    //
    //            default:
    //                ;
    //        }
    //    }
    //}

}
