<?php

namespace App\Dev;

use GdImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Trash
{
    public function test()
    {
        $svg = file_get_contents(public_path('images/test/test.svg'));
        $svgz = gzcompress($svg, 9);
        file_put_contents(public_path('images/test/test.svgz'), $svgz);
        dd(tmr(),strlen($svg), strlen($svgz));
        dd(tmr(), 'test');
    }

}
