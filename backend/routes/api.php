<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelController;

Route::post('/travel-data', [TravelController::class, 'getCityData']);
Route::get('/cities', [TravelController::class, 'getCities']);