<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('retry/{id?}', function ($id = null) {
    $key = $id ?? 'all';
    Artisan::call("queue:retry {$key}");
});

Route::get('failed', function () {
    $failed = Artisan::call('queue:failed');
    dd($failed);
});

Route::get('url', function () {
    $request = Http::post('https://atomic.incfile.com/fakepost', []);
    ddd($request);
});
