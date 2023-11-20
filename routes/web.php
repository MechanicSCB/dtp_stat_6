<?php

use App\Dev\CacheMassFiller;
use App\Dev\Scraper;
use App\Dev\Seeder;
use App\Dev\Trash;
use App\Http\Controllers\AccidentController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\HotspotBalloonController;
use App\Http\Controllers\HotspotLayerController;
use App\Http\Controllers\ImageLayerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// --- DEV ROUTES
Route::get('/test',  [Trash::class, 'test']);
Route::get('/scrap',  [Scraper::class, 'run']);
Route::get('/seed',  [Seeder::class, 'seed']);
Route::get('/cache',  [CacheMassFiller::class, 'fill']);
// --- \DEV ROUTES


// MAP
Route::get('/',  [AccidentController::class, 'index']);
Route::get('/storage/tiles/png/{req}/{z}/{x}_{y}.png', [ImageLayerController::class, 'getTileImage']);
Route::get('/storage/tiles/svg/{req}/{z}/{x}_{y}.svg', [ImageLayerController::class, 'getTileImage']);
Route::get('/storage/tiles/webp/{req}/{z}/{x}_{y}.webp', [ImageLayerController::class, 'getTileImage']);
Route::get('/storage/tiles/hotspot/{filterKey}/{z}/{x}_{y}.js', [HotspotLayerController::class, 'getData']);
Route::get('/get-hotspot-balloon', [HotspotBalloonController::class, 'getHotspotBalloon']);
Route::get('/accidents/{accident}', [AccidentController::class, 'show'])->name('accidents.show');
Route::get('/get-stat', [AccidentController::class, 'getRegionStat']);

// PAGES
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);
Route::get('/charts', [ChartController::class, 'index']);
Route::get('/download', [PageController::class, 'download']);
Route::get('/about', [PageController::class, 'about']);

// JETSTREAM
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
