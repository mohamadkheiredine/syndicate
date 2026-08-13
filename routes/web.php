<?php

use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web_content'])->group(function () {

    // HOME
    Route::get('/', [HomeController::class, 'index'])->name('web.home');

    Route::match(['get', 'post', 'delete'], '/delete-account', [AccountController::class, 'handle'])->name('web.accounts.delete');

    // PLACEHOLDER PAGES
    // Empty for now - linked from the homepage (nav, CTA boxes, article grid) so
    // route() resolves everywhere. Each one gets its own real controller/view
    // when that page is built.
    Route::view('/aboutus', 'web.pages.aboutus')->name('web.aboutus');
    Route::view('/activities', 'web.pages.activities')->name('web.activities');
    Route::view('/offers', 'web.pages.offers')->name('web.offers');
    Route::view('/news', 'web.pages.news')->name('web.news');
    Route::view('/page-donate', 'web.pages.page-donate')->name('web.page-donate');
    Route::view('/form', 'web.pages.form')->name('web.form');
    Route::view('/contact', 'web.pages.contact')->name('web.contact');
    Route::view('/previous-members', 'web.pages.previous-members')->name('web.previous-members');
    Route::view('/terms', 'web.pages.terms')->name('web.terms');
    Route::view('/user-login', 'web.pages.user-login')->name('web.user-login');
    Route::get('/logout', function () {
        session()->forget('web_user_id');
        return redirect()->route('web.home');
    })->name('web.logout');

});
