<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cms\Auth\LoginController;
use App\Http\Controllers\Cms\Base\ProfileController;
use App\Http\Controllers\Cms\Base\CmsSettingController;
use App\Http\Controllers\Cms\Base\AdminController;
use App\Http\Controllers\Cms\Base\DashboardController;
use App\Http\Controllers\Cms\Base\RoleController;
use App\Http\Controllers\Cms\Base\PermissionController;
use App\Http\Controllers\Cms\Base\SimulationController;
use App\Http\Controllers\Cms\UserController;
use App\Http\Controllers\Cms\SyndicateUserController;
use App\Http\Controllers\Cms\SupportCategoryController;
use App\Http\Controllers\Cms\SupportController;
use App\Http\Controllers\Cms\FaqController;
use App\Http\Controllers\Cms\Base\FixedSectionController;
use App\Http\Controllers\Cms\Base\CountryController;
use App\Http\Controllers\Cms\Base\SocialMediaController;
use App\Http\Controllers\Cms\Base\TermController;
use App\Http\Controllers\Cms\Base\PrivacyController;
use App\Http\Controllers\Cms\Base\PushNotificationController;
use App\Http\Controllers\Cms\Base\EmailNotificationController;
use App\Http\Controllers\Cms\Base\SmsNotificationController;
use App\Http\Controllers\Cms\Base\WhatsappNotificationController;
use App\Http\Controllers\Cms\Base\MaintenanceController;
use App\Http\Controllers\Cms\Base\SettingController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Admin Routes for guests admin
|--------------------------------------------------------------------------
|
*/

Route::redirect('/admin', '/admin/login');

Route::middleware(['guest:admin', 'admin_content'])->prefix('admin')->name('admin.')->group(function () {

    // ADMIN LOGIN ROUTE
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');

});

Route::middleware(['auth:admin', 'set_admin_as_default_guard', 'admin_content'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // PROFILE PAGE
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::put('/password', [ProfileController::class, 'password'])->name('password');
        });

        // CMS SETTINGS
        Route::prefix('cms-settings')->name('cms-settings.')->group(function () {
            Route::get('/', [CmsSettingController::class, 'edit'])->name('edit');
            Route::put('/', [CmsSettingController::class, 'update'])->name('update');
        });

        // ADMIN LOGOUT ROUTE
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        // DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ADMINS MANAGEMENT
        Route::post('admins/block', [AdminController::class, 'block'])->name('admins.block');
        Route::resource('admins', AdminController::class)->except(['show']);
        Route::post('admins/update-timezone', [AdminController::class, 'update_timezone'])->name('admins.update-timezone');

        // ROLES
        Route::resource('roles', RoleController::class);

        // PERMISSIONS
        Route::resource('permissions', PermissionController::class)->except(['show']);

        // SIMULATION
        Route::prefix('simulation')->name('simulation.')->group(function () {
            Route::get('/', [SimulationController::class, 'index'])->name('index');
            Route::post('/', [SimulationController::class, 'store'])->name('store');
            Route::get('/destroy', [SimulationController::class, 'destroy'])->name('destroy');
        });

        // USERS MANAGEMENT
        Route::post('users/block', [UserController::class, 'blocked'])->name('users.blocked');
        Route::resource('users', UserController::class);

        // SYNDICATE USERS
        Route::post('syndicate-users/activate', [SyndicateUserController::class, 'activate'])->name('syndicate-users.activate');
        Route::get('syndicate-users/{id}/reset-password', [SyndicateUserController::class, 'resetPassword'])->name('syndicate-users.reset-password');
        Route::post('syndicate-users/reset-password', [SyndicateUserController::class, 'resetPasswordUpdate'])->name('syndicate-users.reset-password.update');
        Route::get('syndicate-users/{id}/image/remove', [SyndicateUserController::class, 'imageRemove'])->name('syndicate-users.image.remove');
        Route::get('syndicate-users/export/csv', [SyndicateUserController::class, 'exportCsv'])->name('syndicate-users.export.csv');
        Route::get('syndicate-users/export/excel', [SyndicateUserController::class, 'exportExcel'])->name('syndicate-users.export.excel');
        Route::get('syndicate-users/export/pdf', [SyndicateUserController::class, 'exportPdf'])->name('syndicate-users.export.pdf');
        Route::resource('syndicate-users', SyndicateUserController::class)->except(['show']);

        // SUPPORT CATEGORIES
        Route::resource('support-categories', SupportCategoryController::class);

        // SUPPORT (Contact Forms)
        Route::resource('support', SupportController::class)->only(['index', 'show', 'destroy']);

        // FAQs
        Route::prefix('faqs')->name('faqs.')->group(function () {
            Route::post('/publish', [FaqController::class, 'publish'])->name('publish');
            Route::get('/order', [FaqController::class, 'order'])->name('order');
            Route::post('/order', [FaqController::class, 'orderSubmit']);
        });
        Route::resource('faqs', FaqController::class);

        // FIXED SECTIONS
        Route::resource('fixed-sections', FixedSectionController::class);

        // COUNTRIES
        Route::prefix('countries')->name('countries.')->group(function () {
            Route::post('/publish', [CountryController::class, 'publish'])->name('publish');
        });
        Route::resource('countries', CountryController::class);

        // SOCIAL MEDIAS
        Route::prefix('social-media')->name('social-media.')->group(function () {
            Route::post('/publish', [SocialMediaController::class, 'publish'])->name('publish');
            Route::get('/order', [SocialMediaController::class, 'order'])->name('order');
            Route::post('/order', [SocialMediaController::class, 'orderSubmit']);
        });
        Route::resource('social-media', SocialMediaController::class);

        // PUSH NOTIFICATIONS
        Route::prefix('push-notifications')->name('push-notifications.')->group(function () {
            Route::get('/', [PushNotificationController::class, 'index'])->name('index');
            Route::post('/bulk-push', [PushNotificationController::class, 'bulk_push'])->name('bulk');
            Route::post('/single-push', [PushNotificationController::class, 'single_push'])->name('single');
        });


        // EMAIL NOTIFICATIONS
        Route::prefix('email-notifications')->name('email-notifications.')->group(function () {
            Route::get('/', [EmailNotificationController::class, 'index'])->name('index');
            Route::post('/bulk-email', [EmailNotificationController::class, 'bulk_email'])->name('bulk');
            Route::post('/single-email', [EmailNotificationController::class, 'single_email'])->name('single');
        });

        // SMS NOTIFICATIONS
        Route::prefix('sms-notifications')->name('sms-notifications.')->group(function () {
            Route::get('/', [SmsNotificationController::class, 'index'])->name('index');
            Route::post('/bulk-sms', [SmsNotificationController::class, 'bulk_sms'])->name('bulk');
            Route::post('/single-sms', [SmsNotificationController::class, 'single_sms'])->name('single');
        });

        // WHATSAPP NOTIFICATIONS
        Route::prefix('whatsapp-notifications')->name('whatsapp-notifications.')->group(function () {
            Route::get('/', [WhatsappNotificationController::class, 'index'])->name('index');
            Route::post('/bulk-whatsapp', [WhatsappNotificationController::class, 'bulk_whatsapp'])->name('bulk');
            Route::post('/single-whatsapp', [WhatsappNotificationController::class, 'single_whatsapp'])->name('single');
        });

        // MAINTENANCE
        Route::prefix('maintenance')->name('maintenance.')->group(function () {
            Route::get('/', [MaintenanceController::class, 'edit'])->name('edit');
            Route::put('/', [MaintenanceController::class, 'update'])->name('update');
            Route::get('/{id}/image/remove', [MaintenanceController::class, 'imageRemove'])->name('image.remove');
        });

        // SETTINGS
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'edit'])->name('edit');
            Route::put('/', [SettingController::class, 'update'])->name('update');
        });
});
