<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\GenderController;
use App\Http\Controllers\Api\FixedSectionController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\SocialMediaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\PushController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

// AUTHENTICATION
Route::group(['prefix' => 'user'], function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/verification', [VerificationController::class, 'verify']);
});

// COUNTRIES
Route::get('countries', [CountryController::class, 'index']);

// CONTACT
Route::controller(ContactController::class)->group(function () {
    Route::get('support-categories', 'support_categories');
    Route::post('contact', 'contact');
});

// GENDERS
Route::get('genders', [GenderController::class, 'index']);

// ABOUT
Route::get('about', [FixedSectionController::class, 'about']);

// FAQs
Route::get('faqs', [FaqController::class, 'index']);

// TERMS
Route::get('terms', [FixedSectionController::class, 'terms']);

// PRIVACY
Route::get('privacy', [FixedSectionController::class, 'privacy']);

// SOCIAL MEDIA
Route::get('social-media', [SocialMediaController::class, 'index']);

// PRIVACY
Route::get('settings', [SettingController::class, 'index']);

// Authenticated routes
Route::middleware(['auth:sanctum', 'check_user_app'])->group(function () {

    // USER ROUTES
    Route::prefix('user')->controller(UserController::class)->group(function () {
        // USER PROFILE
        Route::prefix('profile')->group(function () {
            Route::get('/', 'profile');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete_account_password');
            Route::get('/delete', 'delete_account_pin');
            Route::post('/delete', 'delete_account');
        });

        // USER LOGOUT
        Route::get('logout', 'logout');
    });

    // ADDRESSES ROUTES
    Route::prefix('address')->controller(AddressController::class)->group(function () {
        Route::get('get', 'get');
        Route::post('add', 'add');
        Route::post('edit', 'edit');
        Route::post('delete', 'delete');
        Route::post('set-as-default', 'set_as_default');
    });

    // PUSH NOTIFICATION ROUTES
    Route::prefix('push')->controller(PushController::class)->group(function () {
        Route::post('set-player-id', 'set_player_id');
        Route::post('inbox', 'inbox');
        Route::get('unread-count', 'unreadNotificationsCount');
        Route::post('hide', 'hide');
        Route::post('read', 'read');
    });
});
