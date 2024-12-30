<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\userController;
use App\Http\Controllers\registerController;

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
    return view('welcome');
});


// Routing untuk login
Route::get('/login', [loginController::class, 'indexLogin'])->name('login');

Route::get('/login/google', [loginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/callback', [loginController::class, 'handleGoogleCallback']);

Route::post('/login', [loginController::class, 'login'])->name('login');
Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('admin/dashboard',[adminController::class,'index'])->name('admin.dashboard');
});
Route::middleware(['auth','role:user'])->group(function(){
    Route::get('user/dashboard',[userController::class,'index'])->name('user.dashboard');
    Route::get('/search', [userController::class, 'search'])->name('search');
    Route::get('/history', [userController::class, 'history'])->name('history');
    Route::get('/book/{id}', [userController::class, 'bookDetails'])->name('book.details');
    Route::post('/borrow/{id}', [userController::class,'borrowBook'])->name('book.borrow');
    Route::post('/export-pdf',[userController::class,'exportPDF'])->name('export.pdf');
    Route::post('logout',[loginController::class,'logout'])->name('logout');
});

// Routing untuk register
Route::get('/register', [registerController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [registerController::class, 'storeData'])->name('register.store');

// Routing untuk forgot password
Route::get('/forgot-password', [loginController::class,'indexForgotPass'])->name('forgot-pass');
Route::post('/forgot-password', [loginController::class,'forgotPassword'])->name('forgot-pass.send');

// Routing untuk reset password
Route::get('/konfirmasi-reset', [loginController::class,'indexKonfirmasiReset'])->name('konfirmasi.reset');
Route::get('/reset-password/{token}', [loginController::class,'indexResetPass'])->name('password.reset');
Route::post('/reset-password', [loginController::class,'resetPassword'])->name('password.update');