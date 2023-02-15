<?php

namespace App\Http\Controllers;

use App\Classes\GeoCalc;
use App\Classes\TileHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotspotBalloonController extends Controller
{
    protected TileHandler $tileHandler;
    private GeoCalc $geoCalc;

    public function __construct()
    {
        $this->tileHandler = new TileHandler();
        $this->geoCalc = new GeoCalc();
    }

    public function getHotspotBalloon(Request $request): string
    {
        $balloonContent = $this->getBalloonContent($request);

        $data = [
            'balloonContent' => $balloonContent,
        ];

        $json = json_encode($data);

        header('Content-Type: application/json');

        return $json;
    }

    public function getBalloonContent(Request $request): string
    {
        $limit = 1000;
        $quadkey = $request['quadkey'];
        $tileNumber = $this->geoCalc->getTileNumberFromQuadkey($quadkey);
        $request['x'] = $tileNumber['x'];
        $request['y'] = $tileNumber['y'];
        $request['z'] = $tileNumber['z'];

        $tileAccidents = $this->tileHandler->getTileAccidents($request, 0);

        $pointListHtml = $pointContentSrcHtml = '';

        foreach (array_slice($tileAccidents, 0, $limit) as $accident) {
            $info = json_decode($accident['info'],1);
            $active = is_null(@$active) ? 'active' : '';
            $id = $accident['id'];
            [$date, $time] = explode(' ',$accident['datetime']);
            $dateFormatted = date('d.m.Y', strtotime($date));
            $time = Str::beforeLast($time, ':');
            $pointListHtml .= "<a class='$active' onclick='showPointContent($id, this)'>$date</a>";
            $pointDataHtmls[$id] = "<h2>$date</h2>
                                <h3>{$info['category']}</h3>
                                <div class='datetime'>$dateFormatted, $time</div>
                                <div class='address'>{$info['address']}</div>";

            if($info['injured_count']){
                $pointDataHtmls[$id] .= "<div class='severity injured'>пострадало: {$info['injured_count']} чел.</div>";
            }

            if($info['dead_count']){
                $pointDataHtmls[$id] .= "<div class='severity dead'>погибло: {$info['dead_count']} чел.</div>";
            }

            $pointDataHtmls[$id] .= "<a href='/accidents/$id' target='_blank'>Подробности ДТП</a>";
            $pointContentSrcHtml .= "<div class='point-content-srs' id='content$id'>$pointDataHtmls[$id]</div>";
        }

        $balloonContent = "<div class='balloon'>";
        $balloonContent .= "<div id='point-list'>";
        $balloonContent .= $pointListHtml;
        $balloonContent .= "</div>";
        $balloonContent .= "<div id='point-content-block'>";
        $balloonContent .= head($pointDataHtmls ?? []);
        $balloonContent .= "</div>";
        $balloonContent .= $pointContentSrcHtml;
        $balloonContent .= "</div>";

        return $balloonContent;
    }
}
