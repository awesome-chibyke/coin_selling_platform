<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyTestControllerr;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Roles\RolesController;
use App\Http\Middleware\AccountActivationGuard;
use App\Http\Controllers\Mails\BulkMailController;
use App\Http\Controllers\Roles\AddRolesController;
use App\Http\Controllers\Roles\UserTypeController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Referal\ReferalController;
use App\Http\Controllers\Support\SupportController;
use App\Http\Controllers\Front\FrontPagesController;
use App\Http\Controllers\NoAuth\ManageNoAuthPayments;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Front\DisplayBannerController;
use App\Http\Controllers\Auth\PasswordManagerController;
use App\Http\Controllers\Settings\AppSettingsController;
use App\Http\Controllers\Support\SupportCategoryController;
use App\Http\Controllers\Unsubscribe\UnsubscribeController;
use App\Http\Controllers\Beneficiaries\BeneficiariesController;
use App\Http\Controllers\ConversionRate\CoinConversionRateController;
use App\Http\Controllers\AccountActivation\AccountActivationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clearC', function (){
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    return 'cache cleared succesfully';
});

//front page route
Route::get('/', [FrontPagesController::class, 'index'])->name('welcome');
Route::get('/contact', [FrontPagesController::class, 'contact'])->name('contact');
Route::get('/terms', [FrontPagesController::class, 'terms'])->name('terms');
Route::get('/policy', [FrontPagesController::class, 'policy'])->name('policy');

Route::get('/test-it', [MyTestControllerr::class, 'index'])->name('test-it');

Route::get('/track-payment/{invoice_id}', [PaymentController::class, 'paymentInvoice'])->name('track-payment');//show blank page
Route::get('/track-payment-async/{invoice_id}', [FrontPagesController::class, 'checkTransaction'])->name('track-payment-async');//show blank page

//front page payment Routes
Route::post('/send-payment-no-auth', [ManageNoAuthPayments::class, 'store'])->name('send-payment-no-auth');
Route::post('/send-payment-authicate', [ManageNoAuthPayments::class, 'authenticateAndStorePayment'])->name('send-payment-authicate');
Route::get('/get-coin-details/{code}', [FrontPagesController::class, 'show'])->name('get-coin-details');
Route::get('/get-coin-details-two/{code}/{amount}', [FrontPagesController::class, 'getamountDetails'])->name('get-coin-details-two');

// unsubcribe
Route::get('/unsubscribe/{userId}', [UnsubscribeController::class, 'create'])->name('unsubscribe');
Route::post('/store-unsubscribe/{userId}', [UnsubscribeController::class, 'store'])->name('store-unsubscribe');


Route::get('/account_activation/{user_unique_id}', [AccountActivationController::class, 'index'])->name('account_activation');
Route::get('/resend_activation_code/{user_unique_id}', [AccountActivationController::class, 'resendActivationCode'])->name('resend_activation_code');
Route::post('/activate_account', [AccountActivationController::class, 'store'])->name('activate_account');


//password reset
Route::get('/initiate-password-reset', [ForgotPasswordController::class, 'initiatePasswordReset'])->name('initiate-password-reset');
Route::post('/send-password-reset-token', [ForgotPasswordController::class, 'sendForgotPasswordMail'])->name('send-password-reset-token');
Route::get('/password-reset-token-form/{user_unique_id}', [ForgotPasswordController::class, 'showPasswordTokenForm'])->name('password-reset-token-form');//form
Route::get('/re-send-password-reset-token/{user_unique_id}', [ForgotPasswordController::class, 'reSendForgotPasswordMail'])->name('re-send-password-reset-token');
Route::post('/validate-password-reset-token', [ForgotPasswordController::class, 'validatePasswordResetToken'])->name('validate-password-reset-token');
Route::get('/password-reset-form/{token}/{user_unique_id}', [ForgotPasswordController::class, 'showPasswordResetForm'])->name('password-reset-form');
Route::post('/reset-user-password', [ForgotPasswordController::class, 'resetUserPassword'])->name('reset-user-password');


//control email preference for the users
Route::get('/update_email_preference', [EmailPreferenceControllerionController::class, 'store'])->name('update_email_preference');

Auth::routes();


Route::middleware(['auth', AccountActivationGuard::class])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/chart-data/{filterKeyword?}/{first_date?}/{second_date?}', [HomeController::class, 'getBartChartDetails'])->name('chart-data');
});

//profile
Route::middleware(['auth', AccountActivationGuard::class])->group(function () {
    Route::get('/profile/{user_unique_id?}', [ProfileController::class, 'index'])->name('profile');
    Route::get('/edit-profile/{user_unique_id?}', [ProfileController::class, 'edit'])->name('edit-profile');
    Route::post('/update-profile/{user_unique_id?}', [ProfileController::class, 'update'])->name('update-profile');
});


//bank
Route::middleware(['auth', AccountActivationGuard::class])->group(function () {
    Route::get('/create-bank/{user_unique_id?}', [BeneficiariesController::class, 'create'])->name('create-bank');//show bank page
    Route::get('/view-bank/{user_unique_id?}', [BeneficiariesController::class, 'index'])->name('view-bank');//show bank page
    Route::get('/verify-bank-information/{user_unique_id?}', [BeneficiariesController::class, 'edit'])->name('verify-bank-information');//verify bank information
    Route::post('/save-bank/{user_unique_id}', [BeneficiariesController::class, 'store'])->name('save-bank');//save bank information
    Route::post('/verify-bank-account', [BeneficiariesController::class, 'validateAccountDetails'])->name('verify-bank-account');//save bank information
    Route::get('/activate-bank/{unique_id}', [BeneficiariesController::class, 'activate'])->name('activate-bank');//save bank information
    Route::get('/delete-bank/{unique_id}', [BeneficiariesController::class, 'destroy'])->name('delete-bank');//save bank information
});


//payment section
Route::middleware(['auth', AccountActivationGuard::class])->group(function () {
    Route::get('/create-payment', [PaymentController::class, 'displayCoinsAvailableForPayment'])->name('create-payment');//show bank page
    Route::post('/initialize-payment', [PaymentController::class, 'initializePayment'])->name('initialize-payment');//show bank page
    Route::get('/payment-invoice/{invoice_id}', [PaymentController::class, 'paymentInvoice'])->name('payment-invoice');//show bank page
    Route::get('/payment-history/{type_of_transaction}/{paymentStatus?}/{startDate?}/{endDate?}', [PaymentController::class, 'paymentHistory'])->name('payment-history');//show bank page
    Route::post('/delete-payments', [PaymentController::class, 'destroy'])->name('delete-payments');//show bank page
});


//create and update the coin converstion rate
Route::middleware(['auth', AccountActivationGuard::class])->group(function () {
    Route::get('/create-coin-converstion-rate', [CoinConversionRateController::class, 'create'])->name('create-coin-converstion-rate');//show bank page
    Route::post('/store-update-payment', [CoinConversionRateController::class, 'storeUpdatePayment'])->name('store-update-payment');//show bank page
});


//settings route
Route::middleware(['auth', AccountActivationGuard::class, 'admin_check'])->group(function () {
    Route::get('/settings', [AppSettingsController::class, 'showAppSettings'])->name('settings');//settings
    Route::post('/update-settings', [AppSettingsController::class, 'updateAppSettings'])->name('update-settings');//settings
});


//supoort route
Route::middleware(['auth', AccountActivationGuard::class])->group(function () {
    Route::get('/support-summary/{userUniqueId?}', [SupportController::class, 'index'])->name('support-summary');//support summary
    Route::get('/more-details/{supportId?}', [SupportController::class, 'index'])->name('more-details');//support summary
    Route::get('/create-support/{supportUniqueId?}', [SupportController::class, 'create'])->name('create-support');//support summary
    Route::get('/close-ticket/{supportUniqueId?}', [SupportController::class, 'updateToClose'])->name('close-ticket');//support summary
    Route::post('/store-support', [SupportController::class, 'storeInitialMessage'])->name('store-support');
    Route::post('/store-continious-support', [SupportController::class, 'storeContiniousMessage'])->name('store-continious-support');
});

//create support category
Route::middleware(['auth', AccountActivationGuard::class, 'admin_check'])->group(function () {
    Route::get('/view-category', [SupportCategoryController::class, 'index'])->name('view-category');//support summary
    Route::get('/create-support-catgory', [SupportCategoryController::class, 'create'])->name('create-support-category');//support summary
    Route::get('/edit-ticket-category/{unique_id}', [SupportCategoryController::class, 'edit'])->name('edit-ticket-category');//support summary
    Route::get('/delete-ticket-category/{unique_id}', [SupportCategoryController::class, 'destroy'])->name('delete-ticket-category');//support summary
    Route::post('/update-ticket-category/{unique_id}', [SupportCategoryController::class, 'update'])->name('update-ticket-category');//support summary
    Route::post('/store-category', [SupportCategoryController::class, 'store'])->name('store-category');//support summary
});


//admin controller routes
Route::middleware(['auth', AccountActivationGuard::class])->group(function () {
     //admin routes
    Route::get('/all-users', [AdminController::class, 'showAllUsers'])->name('all-users');
    Route::get('/all-admin', [AdminController::class, 'showAllAdmin'])->name('all-admin');
    Route::get('/all-super-admin', [AdminController::class, 'showAllSuperAdmin'])->name('all-super-admin');
    Route::post('/manage-account', [AdminController::class, 'manageAccount'])->name('manage-account');
    Route::post('/manage-user-level/{userUniqueId}', [AdminController::class, 'update'])->name('manage-user-level');
    Route::get('/list-of-users/{type_of_users?}', [AdminController::class, 'usersListInterface'])->name('list-of-users');
});

//bulk mail sending
Route::middleware(['auth', AccountActivationGuard::class, 'admin_check'])->group(function () {
    Route::get('/send-mail', [BulkMailController::class, 'index'])->name('send-mail');
    Route::post('/send-message', [BulkMailController::class, 'store'])->name('send-message');
});


//change password
Route::middleware(['auth', AccountActivationGuard::class])->group(function () {
    Route::get('/change-password/{userId}', [PasswordManagerController::class, 'create'])->name('change-password');
    Route::post('/change-password-auth/{userId}', [PasswordManagerController::class, 'store'])->name('change-password-auth');
});

//change password
Route::middleware(['auth', AccountActivationGuard::class])->group(function () {
    Route::get('/referals/{userId?}', [ReferalController::class, 'index'])->name('referals');
});

//upload the display picture
Route::middleware(['auth', AccountActivationGuard::class, 'admin_check'])->group(function () {
    Route::get('/upload-display-banner', [DisplayBannerController::class, 'create'])->name('upload-display-banner');
    Route::get('/view-display-banner', [DisplayBannerController::class, 'index'])->name('view-display-banner');
    Route::get('/edit-display-banner/{unique_id}', [DisplayBannerController::class, 'edit'])->name('edit-display-banner');
    Route::get('/delete-display-banner/{unique_id}', [DisplayBannerController::class, 'destroy'])->name('delete-display-banner');
    Route::get('/update-display-banner-status/{unique_id}', [DisplayBannerController::class, 'updateDisplayBannerToActive'])->name('update-display-banner-status');
    Route::post('/upload-display-banner', [DisplayBannerController::class, 'store'])->name('upload-display-banner');
    Route::post('/update-display-banner/{unique_id}', [DisplayBannerController::class, 'update'])->name('update-display-banner');
});

//upload the display picture
Route::middleware(['auth', AccountActivationGuard::class, 'admin_check'])->group(function () {
    //add roles area
    Route::get('/add_roles', [RolesController::class, 'create'])->name('add_roles');
    Route::get('/view_all_roles', [RolesController::class, 'index'])->name('view_all_roles');
    Route::post('/store_role', [RolesController::class, 'store'])->name('store_role');
    Route::get('/add_role_for_user/{userTypeId}', [AddRolesController::class, 'index'])->name('add_role_for_user');
    Route::post('/store_role_for_user/{userTypeId}', [AddRolesController::class, 'store'])->name('store_role_for_user');

    //add user type
    Route::get('/add_user_type', [UserTypeController::class, 'create'])->name('add_user_type');
    Route::get('/all_user_type', [UserTypeController::class, 'index'])->name('all_user_type');
    Route::post('/store_user_type', [UserTypeController::class, 'store'])->name('store_user_type');
});