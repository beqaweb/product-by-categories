<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::redirect('/', 'products');
Route::redirect('/home', 'products');

Route::get('products', 'ProductController@index')->name('productList');

Route::middleware('auth')->group(function () {
    Route::middleware('permission:manage category')->group(function () {
        Route::redirect('admin', 'admin/categories');
        Route::get('admin/categories', 'CategoryController@index')->name('categoryList');
        Route::get('admin/categories/new', 'CategoryController@new')->name('newCategoryForm');
        Route::post('admin/categories', 'CategoryController@store')->name('newCategory');
        Route::get('admin/categories/{category}/permissions', 'CategoryController@permissions')->name('categoryPermissions');
        Route::post('admin/categories/{category}/permissions', 'CategoryController@updatePermissions')->name('categoryUpdatePermissions');
        Route::get('admin/categories/{category}/update', 'CategoryController@updateForm')->name('categoryUpdateForm');
        Route::match(['patch', 'put'], 'admin/categories/{category}', 'CategoryController@update')->name('categoryUpdate');
        Route::get('admin/categories/{category}/delete', 'CategoryController@deleteConfirm')->name('categoryDeleteConfirm');
        Route::delete('admin/categories/{category}', 'CategoryController@delete')->name('categoryDelete');
    });

    Route::get('admin/products/new', 'ProductController@new')->name('newProductForm');
    Route::get('admin/products/forbidden', 'ProductController@forbidden')->name('productForbidden');
    Route::middleware(['can-manage-product'])->group(function () {
        Route::post('admin/products', 'ProductController@store')->name('newProduct');
        Route::get('admin/products/{product}/update', 'ProductController@updateForm')->name('productUpdateForm');
        Route::match(['patch', 'put'], 'admin/products/{product}', 'ProductController@update')->name('productUpdate');
        Route::get('admin/products/{product}/delete', 'ProductController@deleteConfirm')->name('productDeleteConfirm');
        Route::delete('admin/products/{product}', 'ProductController@delete')->name('productDelete');
    });
});
