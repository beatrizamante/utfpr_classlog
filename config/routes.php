<?php

use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\UsersController;
use Core\Router\Route;

// Authentication
Route::get('/', [HomeController::class, 'index'])->name('root');
Route::post('/register', [UsersController::class, 'register'])->name('users.register');
Route::post('/login', [UsersController::class, 'login'])->name('users.login');

Route::middleware('auth')->group(function () {

    Route::get('/logout', [UsersController::class, 'destroy'])->name('users.logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
