<?php

header('Content-Type: application/json');

use App\Controllers\BlockController;
use App\Controllers\ClassRoomController;
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


    Route::get('/blocks', [BlockController::class, 'index'])->name('blocks');
    Route::post('/blocks', [BlockController::class, 'create'])->name('blocks.create');
    Route::get('/blocks/{id}', [BlockController::class, 'show'])->name('blocks.show');
    Route::put('/blocks/{id}', [BlockController::class, 'update'])->name('blocks.update');
    Route::delete('/blocks/{id}', [BlockController::class, 'destroy'])->name('blocks.destroy');

    Route::get('/classrooms', [ClassRoomController::class, 'index'])->name('classroom');
    Route::post('/classrooms', [ClassRoomController::class, 'create'])->name('classroom.create');
    Route::get('/classrooms/{id}', [ClassRoomController::class, 'show'])->name('classroom.show');
    Route::put('/classrooms/{id}', [ClassRoomController::class, 'update'])->name('classroom.update');
    Route::delete('/classrooms/{id}', [ClassRoomController::class, 'destroy'])->name('classroom.destroy');
});
