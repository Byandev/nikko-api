<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Enums\AccountType;
use Modules\Auth\Http\Controllers\Account\EducationController;
use Modules\Auth\Http\Controllers\Account\WorkExperienceController;
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
use Modules\Auth\Http\Controllers\SaveAccountController;
use Modules\Auth\Http\Middleware\AccountCheck;
use Modules\Auth\Http\Middleware\OptionalAccountCheck;
use Modules\Auth\Http\Middleware\OptionalAuth;
use Modules\Certificate\Http\Controllers\CertificateController;
use Modules\Media\Http\Controllers\MediaController;
use Modules\Portfolio\Http\Controllers\PortfolioController;
use Modules\Project\Http\Controllers\Client\ProjectController;
use Modules\Project\Http\Controllers\Client\ProposalController as ClientProposalController;
use Modules\Project\Http\Controllers\ProposalController;
use Modules\Project\Http\Controllers\SaveProjectController;
use Modules\Skill\Http\Controllers\SkillController;
use Modules\Tool\Http\Controllers\ToolController;

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

Route::get('v1/accounts', [AccountController::class, 'index'])
    ->name('account.index')
    ->middleware([
        OptionalAuth::class,
        OptionalAccountCheck::class.':'.AccountType::CLIENT->value,
    ]);

Route::get('v1/accounts/{account}', [AccountController::class, 'show'])
    ->name('account.show')
    ->middleware([
        OptionalAuth::class,
        OptionalAccountCheck::class.':'.AccountType::CLIENT->value,
    ]);

Route::get('v1/projects', [\Modules\Project\Http\Controllers\ProjectController::class, 'index'])
    ->name('projects.index')
    ->middleware([
        OptionalAuth::class,
        OptionalAccountCheck::class.':'.AccountType::FREELANCER->value,
    ]);

Route::get('v1/projects/{project}', [\Modules\Project\Http\Controllers\ProjectController::class, 'show'])
    ->name('project.show')
    ->middleware([
        OptionalAuth::class,
        OptionalAccountCheck::class.':'.AccountType::FREELANCER->value,
    ]);

Route::apiResource('v1/accounts/{account}/portfolios', PortfolioController::class)
    ->only(['index', 'show'])
    ->names('account.portfolios');

Route::apiResource('v1/accounts/{account}/certificates', CertificateController::class)
    ->only(['index', 'show'])
    ->names('account.certificates');

Route::apiResource('v1/accounts/{account}/work-experiences', WorkExperienceController::class)
    ->only(['index', 'show'])
    ->names('account.work-experiences');

Route::apiResource('v1/accounts/{account}/educations', EducationController::class)
    ->only(['index', 'show'])
    ->names('account.educations');

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('skills', [SkillController::class, 'index'])->name('skills.index');
    Route::get('tools', [ToolController::class, 'index'])->name('tools.index');

    Route::post('/accounts/{account}/save', [SaveAccountController::class, 'store'])
        ->middleware(AccountCheck::class.':'.AccountType::CLIENT->value)
        ->name('account.save');

    Route::delete('/accounts/{account}/un-save', [SaveAccountController::class, 'destroy'])
        ->middleware(AccountCheck::class.':'.AccountType::CLIENT->value)
        ->name('account.un-save');

    Route::middleware([AccountCheck::class.':'.AccountType::FREELANCER->value])->group(function () {
        Route::post('/projects/{project}/save', [SaveProjectController::class, 'store'])
            ->name('projects.save');

        Route::delete('/projects/{project}/un-save', [SaveProjectController::class, 'destroy'])
            ->name('projects.un-save');

        Route::apiResource('portfolios', PortfolioController::class)
            ->only(['store', 'update', 'destroy'])
            ->names('account.portfolios');

        Route::apiResource('certificates', CertificateController::class)
            ->only(['store', 'update', 'destroy'])
            ->names('account.certificates');

        Route::apiResource('work-experiences', WorkExperienceController::class)
            ->only(['store', 'update', 'destroy'])
            ->names('account.work-experiences');

        Route::apiResource('educations', EducationController::class)
            ->only(['store', 'update', 'destroy'])
            ->names('account.educations');

        Route::apiResource('proposals', ProposalController::class)
            ->names('account.proposals');
    });

    Route::apiResource('medias', MediaController::class)->names('medias')->only(['store', 'show', 'destroy']);

    Route::group(['prefix' => 'client', 'middleware' => [AccountCheck::class.':'.AccountType::CLIENT->value]], function () {
        Route::apiResource('projects', ProjectController::class)->names('client.projects');
        Route::apiResource('proposals', ClientProposalController::class)->only(['index', 'show'])->names('client.proposals');
    });
});
