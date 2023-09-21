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
    return Inertia::render('basic/Welcome');
})->middleware('auth');

Route::get('/signup', function() {
    return Inertia::render('users/Signup');
});

Route::post('/signup', [UserController::class, 'createUser']);

Route::get('/verify-email', [VerificationController::class, 'showVerifyCodePage']);

Route::post('/verify-email/', [VerificationController::class, 'verifyVerificationCode']);
