<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\store\ProductController as StoreProductController;

use App\Http\Controllers\store\MemberController;

use App\Http\Controllers\Auth2\RegisterController;
use App\Http\Controllers\Auth2\AuthController;

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

Route::post('/register', [RegisterController::class, 'memberRegister'])->name('member.register');
Route::post('/admin/register', [RegisterController::class, 'adminRegister'])->name('admin.register');

Route::post('/login', [AuthController::class, 'memberLogin'])->name('member.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login') ;

Route::post('/logout', [AuthController::class, 'memberDestory'])->name('member.logout');
Route::post('/admin/logout', [AuthController::class, 'adminDestory'])->name('admin.logout') ;

Route::get('/user', [AuthController::class, 'memberUser'])->middleware('auth:sanctum');;
Route::get('/admin/user', [AuthController::class, 'adminUser']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });