<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Admin\ProductController as AdminProductController;
use Modules\Product\Http\Controllers\ProductController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class)
        ->only(['index', 'show'])
        ->names('products');
});

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::apiResource('products', AdminProductController::class)->names('products');
    });
});
