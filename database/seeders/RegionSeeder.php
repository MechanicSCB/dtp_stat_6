<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = file_get_contents(database_path('seeders/src/regions.csv'));
        $csv = explode("\n",$csv);
        $headers = array_shift($csv);
        $headers = trim($headers, '"');
        $headers = explode('","',$headers);

        $regions = [];

        foreach ($csv as $row){
            $row = str_replace(',NULL,', ',"NULL",', $row);
            $row = trim($row, '"');
            $values = explode('","',$row);
            $values = array_map(fn($v) => $v === 'NULL' ? null : $v, $values);

            if(count($values) !== count($headers)){
                continue;
            }

            $regions[] = array_combine($headers, $values);
        }

        Region::upsert($regions, ['id']);
    }
}
