<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ActivitiesController;
use App\Http\Controllers\Api\OffersController;
use App\Http\Controllers\Api\MembersController;
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
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\SplashSlidersController;
use App\Http\Controllers\Api\AdSlidersController;
use App\Http\Controllers\Api\TermsController;
use App\Http\Controllers\Api\JoinUsController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user'], function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/verification', [VerificationController::class, 'verify']);
});

Route::post('login', [LoginController::class, 'syndicateLogin']);
Route::post('register', [LoginController::class, 'syndicateRegister']);
Route::get('activate/{token}', [LoginController::class, 'activate']);

Route::prefix('profile')->controller(ProfileController::class)->group(function () {
    Route::post('/forgot_password', 'syndicateForgotPassword');
    Route::post('/reset_password', 'syndicateResetPassword');
});


Route::post('activities', [ActivitiesController::class, 'index']);
Route::post('activities/search', [ActivitiesController::class, 'search']);
Route::get('splash-sliders', [SplashSlidersController::class, 'index']);
Route::get('ad-sliders', [AdSlidersController::class, 'index']);
Route::post('join_us', [JoinUsController::class, 'index']);

Route::post('payment/return', [PaymentController::class, 'payment_return'])->name('payment-return');
Route::get('payment/status', [PaymentController::class, 'payment_status'])->name('payment-status');

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
Route::get('terms', [TermsController::class, 'index']);

// PRIVACY
Route::get('privacy', [FixedSectionController::class, 'privacy']);

// SOCIAL MEDIA
Route::get('social-media', [SocialMediaController::class, 'index']);

// PRIVACY
Route::get('settings', [SettingController::class, 'index']);

// PUSH - set_user_push works both logged-out (anonymous device
// registration) and logged-in (Bearer token optional, checked inline),
// matching old exactly - it can't sit behind route middleware.
// set_player_id always requires auth in old, so it's in the auth:sanctum
// group below instead.
Route::post('push/set_user_push', [PushController::class, 'syndicateSetUserPush']);

// Authenticated routes
Route::middleware(['auth:sanctum', 'check_user_app'])->group(function () {

    // Flat paths, matching the old API (/api/logout, /api/delete-account).
    Route::controller(LoginController::class)->group(function () {
        Route::post('logout', 'syndicateLogout');
        Route::post('delete-account', 'syndicateDeleteAccount');
    });

    // GET /api/documents - old required a valid access token for it.
    Route::get('documents', [DocumentController::class, 'index']);

    Route::post('offers', [OffersController::class, 'index']);
    Route::post('offers/search', [OffersController::class, 'search']);

    Route::get('members', [MembersController::class, 'index']);


    Route::post('payment/view', [PaymentController::class, 'view']);

    Route::post('push/set_player_id', [PushController::class, 'set_player_id']);

    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('/get', 'syndicateProfile');
        Route::post('/set', 'syndicateProfileUpdate');
        Route::get('/expences', 'syndicateProfileExpenses');
        Route::post('/change_password', 'syndicateChangePassword');
    });

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

    // PUSH NOTIFICATION ROUTES (generic scaffold - set-player-id moved to
    // the syndicate push group above, this one used its own guard/table)
    Route::prefix('push')->controller(PushController::class)->group(function () {
        Route::post('inbox', 'inbox');
        Route::get('unread-count', 'unreadNotificationsCount');
        Route::post('hide', 'hide');
        Route::post('read', 'read');
    });
});
