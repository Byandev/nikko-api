<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Enums\AccountType;
use Modules\Auth\Http\Controllers\AccountController;
use Modules\Auth\Http\Controllers\ChangeEmailController;
use Modules\Auth\Http\Controllers\ChangePasswordController;
use Modules\Auth\Http\Controllers\EmailVerificationController;
use Modules\Auth\Http\Controllers\ForgotPasswordController;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\LogoutController;
use Modules\Auth\Http\Controllers\ProfileController;
use Modules\Auth\Http\Controllers\RegisterController;
use Modules\Auth\Http\Controllers\ResetPasswordController;
use Modules\Auth\Http\Middleware\AccountCheck;
use Modules\Media\Http\Controllers\MediaController;
use Modules\Portfolio\Http\Controllers\PortfolioController;
use Modules\Skill\Http\Controllers\SkillController;

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

Route::name('auth.')->prefix('v1/auth')->group(function () {
    Route::post('/login', LoginController::class)->name('login');
    Route::post('/register', RegisterController::class)->name('register');
    Route::post('/forgot-password', ForgotPasswordController::class)->name('forgot-password');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('reset-password');
    Route::post('/reset-password/check', [ResetPasswordController::class, 'check'])->name('reset-password.check');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', LogoutController::class)->name('logout');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::post('/change-email', [ChangeEmailController::class, 'change'])->name('change-email');
        Route::post('/change-email/verify', [ChangeEmailController::class, 'verify'])->name('change-email.verify');

        Route::post('/change-password', ChangePasswordController::class)->name('change-password');
        Route::post('/change-password', ChangePasswordController::class)->name('change-password');
        Route::post('email-verification/verify', [EmailVerificationController::class, 'verify'])->name('email-verification.verify');
        Route::post('email-verification/resend', [EmailVerificationController::class, 'resend'])->name('email-verification.resend');

        Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('account.update');
    });
});

Route::get('v1/accounts/{account}', [AccountController::class, 'show'])->name('account.show');
Route::apiResource('v1/accounts/{account}/portfolios', PortfolioController::class)
    ->only(['index', 'show'])
    ->names('account.portfolios');

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('skills', [SkillController::class, 'index'])->name('skills.index');
    Route::apiResource('medias', MediaController::class)->names('medias')->only(['store', 'show', 'destroy']);

    Route::apiResource('portfolios', PortfolioController::class)
        ->only(['store', 'update', 'destroy'])
        ->middleware(AccountCheck::class.':'.AccountType::FREELANCER->value)
        ->names('account.portfolios');
});
