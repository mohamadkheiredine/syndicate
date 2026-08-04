<?php

use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

// HOME
Route::get('/', [HomeController::class, 'index'])->name('web.home');

Route::match(['get', 'post', 'delete'], '/delete-account', [AccountController::class, 'handle'])->name('web.accounts.delete');
