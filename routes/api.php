<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\ProductRateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/product-filter', [ProductController::class,'productsFilter']);
Route::delete('/remove-cart-item/{orderProduct}', [OrderProductController::class,'destroy']);
Route::post('/update-cart-item', [OrderProductController::class,'updateCar']);
Route::post('/cart', [OrderProductController::class,'store']);

Route::get('/cart', [OrderProductController::class,'index']);

Route::get('/products', [ProductController::class,'index']);
Route::get('products/{product}', [ProductController::class,'show']);
Route::get('product-types-parent', [ProductTypeController::class,'typeParent']);
Route::resource('product-types', ProductTypeController::class);
Route::get('products-type-slug/{slug}', [ProductTypeController::class,'productTypeSlug']);
Route::resource('orders', OrderController::class);
Route::resource('order-product', OrderProductController::class);
Route::post('products', [ProductController::class,'store']);
Route::get('/orders-client', [OrderController::class,'ordersclient']);
Route::post('/rating', [ProductRateController::class,'store']);

Route::post('/deleteFile', [ProductController::class,'deleteFile']);
Route::post('/addFile', [ProductController::class,'addFile']);
Route::delete('products/{product}', [ProductController::class,'destroy']);
Route::put('products/{product}', [ProductController::class,'update']);

Route::middleware(['customAuth'])->group(function () {



});

Route::get('/bestviews', [ProductController::class,'bestView']);
Route::get('/bestrate', [ProductController::class,'bestRate']);
Route::post('/bestproduct', [ProductController::class,'best']);
Route::post('/getsellByPeriode', [OrderController::class,'sellerPeriode']);
Route::get('/getsellAllTime', [ProductController::class,'sellerAlltime']);
Route::get('/getuserSeller', [OrderController::class,'UserBestSeller']);
