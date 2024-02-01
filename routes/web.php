<?php

use App\Http\Controllers\ecpayController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/receive', [ecpayController::class, 'receive']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/token', function () {
    return csrf_token();
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//這將自動生成 index, create, store, show, edit, update, destroy 這些動作的路由。
Route::resource('category', CategoryController::class)->only(['index', 'store', 'edit', 'update', 'destroy']); //生成產品類別路由
Route::resource('member', MemberController::class); //生成會員路由
Route::resource('order', OrderController::class); //生成訂單路由

require __DIR__ . '/auth.php';
