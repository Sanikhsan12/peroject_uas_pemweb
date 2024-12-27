<?php

use App\Http\Controllers\ProfileController;
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
Route::get('/login/forgot-password', [loginController::class,'indexForgotPass'])->name('forgot-pass');
Route::get('/login/google', [loginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/callback', [loginController::class, 'handleGoogleCallback']);

Route::post('/login', [loginController::class, 'login'])->name('login');
Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('admin/dashboard',[adminController::class,'index'])->name('admin.dashboard');
});
Route::middleware(['auth','role:user'])->group(function(){
    Route::get('user/dashboard',[userController::class,'index'])->name('user.dashboard');
});

// Routing untuk register
Route::get('/register', [registerController::class, 'showRegisterForm'])->name('register');
