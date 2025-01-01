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
    Route::get('/admin/books', [adminController::class, 'getAllBooks']);
    Route::get('/admin/borrows', [adminController::class, 'getAllBorrows']);
    Route::get('/admin/returns', [adminController::class, 'getAllReturns']);
    Route::get('/admin/history', [adminController::class, 'history']);
    Route::post('/admin/mark-returned/{id}', [adminController::class, 'markAsReturned']);
    Route::post('/admin/books', [AdminController::class, 'store']);
    Route::put('/admin/books/{id}', [AdminController::class, 'update']);
    Route::delete('/admin/books/{id}', [AdminController::class, 'destroy']);
    Route::post('/admin/export/books', [adminController::class, 'exportBooksPDF'])->name('admin.export.books');
    Route::post('/admin/export/borrows', [adminController::class, 'exportBorrowsPDF'])->name('admin.export.borrows');
    Route::post('/admin/export/returns', [adminController::class, 'exportReturnsPDF'])->name('admin.export.returns');
    Route::post('logout',[loginController::class,'logout'])->name('logout');
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