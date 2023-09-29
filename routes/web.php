<?php

use Illuminate\Support\Facades\Route;
use inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
   return Inertia::render('dashboard/Home');
})->middleware('auth');

Route::get('/welcome', function () {
    return Inertia::render('basic/Welcome');
});

Route::get('/signup', function() {
    return Inertia::render('users/Signup');
});

Route::get('/login', function () {
   return Inertia::render('users/Login');
})->name('login');

Route::get('/reset-password', function () {
    return Inertia::render('users/ResetPassword');
});

Route::post('/get_verification_code', [VerificationController::class, 'getVerificationCode']);

Route::post('/signup', [UserController::class, 'createUser']);

Route::get('/verify-email', [VerificationController::class, 'showVerifyCodePage']);

Route::post('/verify-email/', [VerificationController::class, 'verifyVerificationCode']);

Route::post('/login', [UserController::class, 'loginUser']);

Route::post('/check-email-existence', [VerificationController::class, 'checkEmailExistence']);

Route::post('/verify-email-code', [VerificationController::class, 'verifyEmailCode']);

Route::post('/reset-password', [VerificationController::class, 'resetPassword']);
