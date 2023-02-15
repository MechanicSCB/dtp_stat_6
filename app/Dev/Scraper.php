<?php


namespace App\Dev;


use Illuminate\Support\Facades\Http;

class Scraper
{
    public function run()
    {
        df(tmr(@$this->start), 'scrap');
        // 46 - Моск. обл. (ОКАТО)
        $reqData = '{"maptype":1,"region":"46","date":"[\"MONTHS:12.2022\"]","pok":"1"}';
        $reqData = json_decode($reqData,1);

        $url = "http://stat.gibdd.ru/map/getMainMapData";

        $res = Http::post($url, $reqData)->body();
        $subregions = json_decode($res,1);
        df(tmr(@$this->start), $subregions);

        // 877 - РФ (ОКАТО)
        $reqData = '{"maptype":1,"region":"877","date":"[\"MONTHS:12.2022\"]","pok":"1"}';
        $reqData = json_decode($reqData,1);

        $url = "http://stat.gibdd.ru/map/getMainMapData";

        $res = Http::post($url, $reqData)->body();
        $regions = json_decode($res,1);
        df(tmr(@$this->start), $regions);

        //$reqData = '{"data":"{\"date\":[\"MONTHS:1.2015\",\"MONTHS:2.2015\",\"MONTHS:3.2015\",\"MONTHS:4.2015\",\"MONTHS:5.2015\",\"MONTHS:6.2015\",\"MONTHS:7.2015\",\"MONTHS:8.2015\",\"MONTHS:9.2015\",\"MONTHS:10.2015\",\"MONTHS:11.2015\",\"MONTHS:12.2015\"],\"ParReg\":\"46\",\"order\":{\"type\":\"1\",\"fieldName\":\"dat\"},\"reg\":\"46233\",\"ind\":\"1\",\"st\":\"1\",\"en\":\"16\"}"}';
        //$reqData = json_decode($reqData,1);
        //df(tmr(@$this->start), $reqData);
        $from = '2015_01';
        $to = '2015_01';
        $months = $this->getMonths($from, $to);

        $req = [
            //'date' => ["MONTHS:1.2015","MONTHS:2.2015","MONTHS:3.2015","MONTHS:4.2015","MONTHS:5.2015","MONTHS:6.2015"],
            'date' => $months,
            'ParReg' => 46,
            'order' => ['type' => 1, 'fieldName' => 'dat'],
            'reg' => 46233,
            'ind' => 1,
            'st' => 1, // from position in list
            'en' => 9999, // to position
        ];

        $jsonData = json_encode($req);
        $reqData = ['data' => $jsonData];
        $url = "http://stat.gibdd.ru/map/getDTPCardData";

        $res = Http::post($url, $reqData);
        df(tmr(@$this->start), $res->body());

    }

    private function getMonths(string $from, string $to): array
    {
        // ["MONTHS:1.2015", "MONTHS:2.2015", "MONTHS:3.2016"]
        [$y, $m] = explode('_', $from);
        $m *= 1;

        $months = [];

        do {
            $months[] = "MONTHS:$m.$y";
            $m++;

            if ($m > 12) {
                $y++;
                $m = 1;
            }

            $date = $y . '_' . str_pad($m,'2','0', STR_PAD_LEFT);
        }while ($date <= $to);

        return $months;
    }
}
