<?php

use Illuminate\Http\Request;
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

// API for admin manager

Route::get('categories', 'CategoryController@indexApi');
Route::get('products', 'ProductController@indexApi');

Route::middleware(['auth:api', 'can-manage-product'])->group(function () {
    Route::post('products', 'ProductController@storeApi');
    Route::match(['patch', 'put'], 'products/{product}', 'ProductController@updateApi');
    Route::delete('products/{product}', 'ProductController@deleteApi');
});
