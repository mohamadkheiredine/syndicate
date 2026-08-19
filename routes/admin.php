<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cms\Auth\LoginController;
use App\Http\Controllers\Cms\Base\ProfileController;
use App\Http\Controllers\Cms\Base\CmsSettingController;
use App\Http\Controllers\Cms\Base\LogoController;
use App\Http\Controllers\Cms\Base\AdminController;
use App\Http\Controllers\Cms\Base\DashboardController;
use App\Http\Controllers\Cms\Base\RoleController;
use App\Http\Controllers\Cms\Base\PermissionController;
use App\Http\Controllers\Cms\Base\SimulationController;
use App\Http\Controllers\Cms\UserController;
use App\Http\Controllers\Cms\SyndicateUserController;
use App\Http\Controllers\Cms\MemberPaymentController;
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
use App\Http\Controllers\Cms\Base\ReportsController;
use App\Http\Controllers\Cms\DocumentController;
use App\Http\Controllers\Cms\IncomeController;
use App\Http\Controllers\Cms\ExpenseController;
use App\Http\Controllers\Cms\YearlyPaymentController;
use App\Http\Controllers\Cms\ElectionFeeController;
use App\Http\Controllers\Cms\BannerController;
use App\Http\Controllers\Cms\NewsController;
use App\Http\Controllers\Cms\SyndicateActivityController;
use App\Http\Controllers\Cms\SyndicateOfferController;
use App\Http\Controllers\Cms\SyndicateFamilyController;
use App\Http\Controllers\Cms\Base\OurTeamController;
use App\Http\Controllers\Cms\Base\SyndicateAboutController;
use App\Http\Controllers\Cms\Base\TermsConditionsController;
use App\Http\Controllers\Cms\SyndicateAdvertisementController;
use App\Http\Controllers\Cms\SyndicateOthersAdvertisementController;
use App\Http\Controllers\Cms\HomeSliderController;
use App\Http\Controllers\Cms\AchievementController;
use App\Http\Controllers\Cms\SplashSliderController;
use App\Http\Controllers\Cms\AdSliderController;

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

        // LOGO
        Route::prefix('logo')->name('logo.')->group(function () {
            Route::get('/', [LogoController::class, 'edit'])->name('edit');
            Route::put('/', [LogoController::class, 'update'])->name('update');
        });

        // OUR TEAM
        Route::prefix('our-team')->name('our-team.')->group(function () {
            Route::get('/', [OurTeamController::class, 'edit'])->name('edit');
            Route::put('/', [OurTeamController::class, 'update'])->name('update');
        });

        // ABOUT THE SYNDICATE
        Route::prefix('about-syndicate')->name('about-syndicate.')->group(function () {
            Route::get('/', [SyndicateAboutController::class, 'edit'])->name('edit');
            Route::put('/', [SyndicateAboutController::class, 'update'])->name('update');
        });

        // TERMS & CONDITIONS (terms-conditions, rules, education - same table, different row)
        Route::prefix('terms-conditions/{page}')->name('terms-conditions.')->where(['page' => 'terms-conditions|rules|education'])->group(function () {
            Route::get('/', [TermsConditionsController::class, 'edit'])->name('edit');
            Route::put('/', [TermsConditionsController::class, 'update'])->name('update');
        });

        // GET INVOLVED ADVERTISE
        Route::put('syndicate-advertisement/{id}/toggle-publish', [SyndicateAdvertisementController::class, 'togglePublish'])->name('syndicate-advertisement.toggle-publish');
        Route::resource('syndicate-advertisement', SyndicateAdvertisementController::class)->except(['show']);

        // OTHERS ADVERTISEMENT
        Route::put('syndicate-others-advertisement/{id}/toggle-publish', [SyndicateOthersAdvertisementController::class, 'togglePublish'])->name('syndicate-others-advertisement.toggle-publish');
        Route::resource('syndicate-others-advertisement', SyndicateOthersAdvertisementController::class)->except(['show']);

        // HOME SLIDERS
        Route::put('home-sliders/{id}/toggle-publish', [HomeSliderController::class, 'togglePublish'])->name('home-sliders.toggle-publish');
        Route::resource('home-sliders', HomeSliderController::class)->except(['show']);

        // ACHIEVEMENTS
        Route::put('achievements/{id}/toggle-publish', [AchievementController::class, 'togglePublish'])->name('achievements.toggle-publish');
        Route::resource('achievements', AchievementController::class)->except(['show']);

        // SPLASH SLIDERS
        Route::put('splash-sliders/{id}/toggle-publish', [SplashSliderController::class, 'togglePublish'])->name('splash-sliders.toggle-publish');
        Route::resource('splash-sliders', SplashSliderController::class)->except(['show']);

        // AD SLIDERS
        Route::put('ad-sliders/{id}/toggle-publish', [AdSliderController::class, 'togglePublish'])->name('ad-sliders.toggle-publish');
        Route::resource('ad-sliders', AdSliderController::class)->except(['show']);

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

        // MEMBERS PAYMENT
        Route::get('members-payment/outstanding-years/{user_id}', [MemberPaymentController::class, 'outstandingYears'])->name('members-payment.outstanding-years');
        Route::get('members-payment/export/csv', [MemberPaymentController::class, 'exportCsv'])->name('members-payment.export.csv');
        Route::get('members-payment/export/excel', [MemberPaymentController::class, 'exportExcel'])->name('members-payment.export.excel');
        Route::get('members-payment/export/pdf', [MemberPaymentController::class, 'exportPdf'])->name('members-payment.export.pdf');
        Route::resource('members-payment', MemberPaymentController::class)->only(['index', 'create', 'store', 'destroy']);

        // REPORTING
        Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');

        // BANNERS
        Route::put('banners/{id}/toggle-publish', [BannerController::class, 'togglePublish'])->name('banners.toggle-publish');
        Route::resource('banners', BannerController::class)->except(['show']);

        // NEWS
        Route::put('news/{id}/toggle-publish', [NewsController::class, 'togglePublish'])->name('news.toggle-publish');
        Route::resource('news', NewsController::class)->except(['show']);

        // SYNDICATE ACTIVITIES
        Route::put('syndicate-activities/{id}/toggle-publish', [SyndicateActivityController::class, 'togglePublish'])->name('syndicate-activities.toggle-publish');
        Route::resource('syndicate-activities', SyndicateActivityController::class)->except(['show']);

        // SYNDICATE OFFERS
        Route::put('syndicate-offers/{id}/toggle-publish', [SyndicateOfferController::class, 'togglePublish'])->name('syndicate-offers.toggle-publish');
        Route::resource('syndicate-offers', SyndicateOfferController::class)->except(['show']);

        // SYNDICATE FAMILY
        Route::put('syndicate-family/{id}/toggle-publish', [SyndicateFamilyController::class, 'togglePublish'])->name('syndicate-family.toggle-publish');
        Route::resource('syndicate-family', SyndicateFamilyController::class)->except(['show']);

        // DOCUMENTS
        Route::resource('documents', DocumentController::class)->except(['show']);

        // INCOME
        Route::get('income/export/csv', [IncomeController::class, 'exportCsv'])->name('income.export.csv');
        Route::get('income/export/excel', [IncomeController::class, 'exportExcel'])->name('income.export.excel');
        Route::get('income/export/pdf', [IncomeController::class, 'exportPdf'])->name('income.export.pdf');
        Route::resource('income', IncomeController::class)->except(['show']);

        // EXPENSES
        Route::get('expenses/export/csv', [ExpenseController::class, 'exportCsv'])->name('expenses.export.csv');
        Route::get('expenses/export/excel', [ExpenseController::class, 'exportExcel'])->name('expenses.export.excel');
        Route::get('expenses/export/pdf', [ExpenseController::class, 'exportPdf'])->name('expenses.export.pdf');
        Route::resource('expenses', ExpenseController::class)->except(['show']);

        // YEARLY PAYMENT
        Route::get('yearly-payment/export/csv', [YearlyPaymentController::class, 'exportCsv'])->name('yearly-payment.export.csv');
        Route::get('yearly-payment/export/excel', [YearlyPaymentController::class, 'exportExcel'])->name('yearly-payment.export.excel');
        Route::get('yearly-payment/export/pdf', [YearlyPaymentController::class, 'exportPdf'])->name('yearly-payment.export.pdf');
        Route::resource('yearly-payment', YearlyPaymentController::class)->except(['show']);

        // ELECTION FEES
        Route::get('election-fees/export/csv', [ElectionFeeController::class, 'exportCsv'])->name('election-fees.export.csv');
        Route::get('election-fees/export/excel', [ElectionFeeController::class, 'exportExcel'])->name('election-fees.export.excel');
        Route::get('election-fees/export/pdf', [ElectionFeeController::class, 'exportPdf'])->name('election-fees.export.pdf');
        Route::resource('election-fees', ElectionFeeController::class)->except(['show']);

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
