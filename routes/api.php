<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\store\ProductController as StoreProductController;

use App\Http\Controllers\store\MemberController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Http\Controllers\ecpayController;
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
Route::post('/logistics-update-shipment-info', [ecpayLogisticsController::class, 'updateShipmentInfo']);
Route::post('/logistics-B2Ccancel', [ecpayLogisticsController::class, 'B2CCancel']);
Route::post('/logistics-C2Ccancel', [ecpayLogisticsController::class, 'C2CCancel']);
Route::post('/logistics-query', [ecpayLogisticsController::class, 'queryLogisticsTradeInfo']);

Route::resource('/store/product', StoreProductController::class)->only(['index','show']);
Route::resource('/admin/product', ProductController::class); //後臺管理商品API

Route::post('/ecpay', [ecpayController::class, 'pay']);


Route::post('/store/register', [MemberController::class, 'register'])->name('member.register');
Route::post('/store/login', [MemberController::class, 'login'])->name('member.login');
Route::post('/store/getuser', [MemberController::class, 'getuser'])->name('member.getuser');

Route::get('/user', [MemberController::class, 'index'])->middleware('auth:sanctum');
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });