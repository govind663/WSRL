<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// === Middleware
use App\Http\Middleware\PreventBackHistoryMiddleware;

// === Controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\QrCodedetatilsController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('auth.login');
})->name('/');

// Auth::routes();

// ===== Admin Register
Route::get('register', [RegisterController::class,'register'])->name('admin.register');
Route::post('register/store', [RegisterController::class,'store'])->name('admin.register.store');

// ===== Admin Login/Logout
Route::get('login', [LoginController::class, 'login'])->name('admin.login');
Route::post('login/store', [LoginController::class, 'authenticate'])->name('admin.login.store');
Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');

// ===== Send Password Reset Link
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.forget-password.request');
Route::post('forgot-password/send-email-link', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.forget-password.send-email-link.store');

// ===== Reset Password
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'updatePassword'])->name('admin.password.update');

Route::group(['prefix' => 'admin', 'middleware' => ['auth:web', PreventBackHistoryMiddleware::class]], function () {

    // ===== Admin Dashboard
    Route::get('home', [HomeController::class, 'adminHome'])->name('admin.dashboard');

    // ===== Manage Role
    Route::resource('roles', RoleController::class);

    // ===== Manage User
    Route::resource('users', UserController::class);

    // ===== Manage Product
    Route::resource('products', ProductController::class);

    // ===== Manage QR Code
    Route::resource('qrcode', QrCodeController::class);

    // ===== fetch-avilable-quantity
    Route::post('fetch-avilable-quantity', [HomeController::class, 'fetchAvilableQuantity'])->name('qrcode.fetch-avilable-quantity');

    // ===== dispatch.check-assigned-qr-codes
    Route::post('dispatch/check-assigned-qr-codes', [HomeController::class, 'checkAssignedQrCodes'])->name('dispatch.check-assigned-qr-codes');

    // ===== Manage Distributor
    Route::resource('distributor', DistributorController::class);

    // ===== Manage Dispatch Product
    Route::resource('dispatch', DispatchController::class);

});


// ===== Show QR Code with encrypted
Route::get('/qr/{unique_number}', [QrCodedetatilsController::class, 'show'])->name('qr.show');

// ===== generate opt
Route::post('/generate-otp', [QrCodedetatilsController::class, 'generateOtp'])->name('generate.otp');

// ===== OTP verification
Route::post('/verify-otp', [QrCodedetatilsController::class, 'verifyOtp'])->name('verify.otp');

// ===== Show Generated PDF
Route::get('/qr-codes/pdf/{unique_number}', [QrCodedetatilsController::class, 'showPdf'])->name('qrcode.showPdf');

// ===== Validation Done By Distributor List
Route::get('dispatch/validation-done-by-distributor-list', [QrCodedetatilsController::class, 'validationDoneByDistributorList'])->name('dispatch.validation-done-by-distributor-list');

// ===== Product wise Validation Done By Distributor List
Route::get('dispatch/validation-done-by-distributor/{productId}', [QrCodedetatilsController::class, 'distributorListProductWise'])->name('dispatch.validation-done-by-distributor-report-list');
