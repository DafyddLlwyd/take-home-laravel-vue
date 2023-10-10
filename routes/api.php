<?php

use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\ShoppingCartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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

Route::get('version', function () {
    return response()->json(['version' => config('app.version')]);
});

// Public routes
Route::group(['prefix' => 'public'], function () {
    Route::get('/products', [ProductController::class, 'list'])->name('products');
    Route::get('/product/{productId}', [ProductController::class, 'show'])->name('product');
    Route::get('/cart/{userId}', [ShoppingCartController::class, 'get'])->name('cart');
    Route::post('/cart', [ShoppingCartController::class, 'save'])->name('cart.save');
    Route::post('/cart/submit', [ShoppingCartController::class, 'submitOrder'])->name('cart.submit');
    Route::get('/orders', [OrderController::class, 'show']);
    Route::get('/invoices', [InvoiceController::class, 'show']);
    Route::post('/order/{orderId}/invoice/generate', [InvoiceController::class, 'invoice']);
    Route::get('/order/{orderId}/invoice/download', [InvoiceController::class, 'download'])->name('order.download');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    Log::debug('User:' . serialize($request->user()));
    return $request->user();
});

Route::namespace('App\\Http\\Controllers\\API\V1')->group(function () {
    Route::get('profile', 'ProfileController@profile');
    Route::put('profile', 'ProfileController@updateProfile');
    Route::post('change-password', 'ProfileController@changePassword');
    Route::get('tag/list', 'TagController@list');
    Route::get('category/list', 'CategoryController@list');
    Route::post('product/upload', 'ProductController@upload');

    Route::apiResources([
        'user' => 'UserController',
        'product' => 'ProductController',
        'category' => 'CategoryController',
        'tag' => 'TagController',
    ]);
});
