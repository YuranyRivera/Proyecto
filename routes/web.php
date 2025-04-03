<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelController;
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


Route::get('hola-mundo', function () {
    return view('hola-mundo');
});

Route::get('/', [TravelController::class, 'showForm'])->name('travel.form');
Route::post('/calculate', [TravelController::class, 'calculate'])->name('travel.calculate');