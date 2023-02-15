<?php


namespace App\Classes;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CacheHandler
{
    const USE_CACHE = 'useCache';
    const IGNORE_CACHE = 'ignoreCache';
    protected string $pngTileFolder;

    public function __construct()
    {
        $this->pngTileFolder = storage_path("app/public/tiles/png");
    }

    public function store(string $key, mixed $value, string $type): bool
    {
        if (in_array($type, ['png'])) {
            $imgFolder = $this->pngTileFolder;
            $dir = Str::after($imgFolder, 'app/') . '/' . Str::beforeLast($key, '/');

            if (! file_exists(storage_path($dir))) {
                Storage::makeDirectory($dir);
            }

            imagepng($value, "$imgFolder/$key.png", 9); // save to storage folder

            return true;
        } elseif (in_array($type, ['callback'])) {
            return Storage::put("public/tiles/hotspot/$key.js", $value);
        } else {
            return Cache::put($key, $value);
        }
    }
}
