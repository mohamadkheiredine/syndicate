<?php

use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\ActivityController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\GetInvolvedController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\OfferController;
use App\Http\Controllers\Web\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web_content'])->group(function () {

    // HOME
    Route::get('/', [HomeController::class, 'index'])->name('web.home');

    Route::match(['get', 'post', 'delete'], '/delete-account', [AccountController::class, 'handle'])->name('web.accounts.delete');

    // ABOUT THE SYNDICATE
    Route::get('/aboutus', [AboutController::class, 'show'])->name('web.aboutus');
    Route::get('/previous-members/{year}', [AboutController::class, 'previousMembers'])->name('web.previous-members');
    Route::post('/join-syndicate', [AboutController::class, 'join'])->name('web.join-syndicate');

    // ACTIVITIES, OFFERS, NEWS (logged-in members only)
    Route::middleware(['auth:web_user'])->group(function () {
        Route::get('/activities', [ActivityController::class, 'index'])->name('web.activities');
        Route::get('/activities-details/{id}', [ActivityController::class, 'show'])->name('web.activities.show');

        Route::get('/offers', [OfferController::class, 'index'])->name('web.offers');
        Route::get('/offers-details/{id}', [OfferController::class, 'show'])->name('web.offers.show');

        Route::get('/news', [NewsController::class, 'index'])->name('web.news');
        Route::get('/news-details/{id}', [NewsController::class, 'show'])->name('web.news.show');
    });

    // GET INVOLVED
    Route::get('/page-donate', [GetInvolvedController::class, 'advertise'])->name('web.page-donate');
    Route::get('/form', [GetInvolvedController::class, 'registerForm'])->name('web.form');
    Route::post('/register', [GetInvolvedController::class, 'register'])->name('web.register');
    Route::get('/activate-account', [GetInvolvedController::class, 'activate'])->name('web.activate-account');
    Route::get('/contact', [GetInvolvedController::class, 'contact'])->name('web.contact');
    Route::post('/contact', [GetInvolvedController::class, 'sendContact'])->name('web.contact.send');

    // TERMS, RULES & EDUCATION
    // Ports the old site's terms.php - the three rows of
    // syndicate_terms_conditions rendered as a vertical tab widget.
    Route::get('/terms', [AboutController::class, 'terms'])->name('web.terms');

    // PLACEHOLDER PAGES
    // Empty for now - linked from the homepage (nav, CTA boxes, article grid) so
    // route() resolves everywhere. Each one gets its own real controller/view
    // when that page is built.
    Route::get('/user-login', [AuthController::class, 'show'])->name('web.user-login');
    Route::post('/login', [AuthController::class, 'login'])->name('web.login');
    Route::get('/logout', [AuthController::class, 'logout'])->name('web.logout');

    // MY PROFILE (logged-in members only)
    Route::middleware(['auth:web_user'])->group(function () {
        Route::get('/user-profile', [ProfileController::class, 'show'])->name('web.user-profile');
        Route::post('/user-profile', [ProfileController::class, 'update'])->name('web.user-profile.update');
    });

});
