<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ecpayMapController;
use App\Http\Controllers\ecpayLogisticsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/map', [ecpayMapController::class, 'index']);
Route::post('/map-response', [ecpayMapController::class, 'response']);
Route::resource('logistics', ecpayLogisticsController::class);
Route::post('/logistics-response', [ecpayLogisticsController::class, 'response']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
