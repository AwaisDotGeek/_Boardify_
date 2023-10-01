<?php

use Illuminate\Support\Facades\Route;
use inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\TicTacToeController;
use App\Http\Controllers\ChatsController;

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

Route::get('/tictactoe', function (){
    return Inertia::render('Games/TicTacToe/Game');
})->middleware('auth');

Route::post('/get_verification_code', [VerificationController::class, 'getVerificationCode']);

Route::post('/signup', [UserController::class, 'createUser']);

Route::get('/verify-email', [VerificationController::class, 'showVerifyCodePage']);

Route::post('/verify-email/', [VerificationController::class, 'verifyVerificationCode']);

Route::post('/login', [UserController::class, 'loginUser']);

Route::post('/check-email-existence', [VerificationController::class, 'checkEmailExistence']);

Route::post('/verify-email-code', [VerificationController::class, 'verifyEmailCode']);

Route::post('/reset-password', [VerificationController::class, 'resetPassword']);


// Discussions
Route::get('/chat', [ChatsController::class, 'index']);
Route::get('messages', [ChatsController::class, 'fetchMessages']);
Route::post('messages', [ChatsController::class, 'sendMessage']);

// Tic Tac Toe
// Start a new game
Route::post('/tictactoe/new', [TicTacToeController::class, 'newGame'])->name('tictactoe.new');

// Make a move
Route::post('/tictactoe/move', [TicTacToeController::class, 'makeMove'])->name('tictactoe.move');
