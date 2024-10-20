<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Enums\AccountType;
use Modules\Auth\Http\Middleware\AccountCheck;
use Modules\Portfolio\Http\Controllers\PortfolioController;

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

Route::apiResource('v1/accounts/{account}/portfolios', PortfolioController::class)
    ->only(['index', 'show'])
    ->names('account.portfolios');

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('portfolios', PortfolioController::class)
        ->only(['store', 'update', 'destroy'])
        ->middleware(AccountCheck::class.':'.AccountType::FREELANCER->value)
        ->names('account.portfolios');
});
