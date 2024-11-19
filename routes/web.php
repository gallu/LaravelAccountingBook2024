<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountingBookController;

use Illuminate\Support\Facades\Route;

//
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::post('/login', [HomeController::class, 'login'])->name('login');

// 認可された状態
Route::middleware(['auth'])->group(function () {
	Route::get('/top', [HomeController::class, 'top'])->name('top');
	// 入金登録
	Route::post('/deposit', [AccountingBookController::class, 'deposit'])->name('deposit');
	// 出金登録
	Route::post('/withdrawal', [AccountingBookController::class, 'withdrawal'])->name('withdrawal');
});

// ユーザ登録
Route::prefix('/register')->group(function () {
	Route::get('', [UserController::class, 'register'])->name('register');
	Route::post('', [UserController::class, 'registerPost'])->name('register.post');
	Route::get('/fin', [UserController::class, 'registerFin'])->name('register.fin');
});
