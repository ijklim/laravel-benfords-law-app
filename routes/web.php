<?php

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

Route::get('/sample-chart', function () {
    return view('samples.chart');
});

Route::get('/sample-component', function () {
    return view('samples.component');
});

Route::get('/phpinfo', function () {
    return view('phpinfo');
});

Route::get('/', function () {
    return view('welcome');
});
