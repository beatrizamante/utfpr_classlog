<?php

header('Content-Type: application/json');

use App\Controllers\BlockController;
use App\Controllers\ClassRoomController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\SubjectController;
use App\Controllers\UsersController;
use App\Controllers\UserSubjectsController;
use Core\Router\Route;

// Authentication
Route::get('/', [HomeController::class, 'index'])->name('root');
Route::post('/register', [UsersController::class, 'register'])->name('users.register');
Route::post('/login', [UsersController::class, 'login'])->name('users.login');

Route::middleware('auth')->group(function () {

    Route::get('/logout', [UsersController::class, 'destroy'])->name('users.logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users/professors', [UsersController::class, 'professors'])->name('users.professors');


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

    Route::get('/subjects', [SubjectController::class, 'index'])->name('subject');
    Route::post('/subjects', [SubjectController::class, 'create'])->name('subject.create');
    Route::get('/subjects/{id}', [SubjectController::class, 'show'])->name('subject.show');
    Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('subject.update');
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subject.destroy');

    Route::get('/user-subjects', [UserSubjectsController::class, 'index'])->name('subject.professor');
    Route::post('/user-subjects', [UserSubjectsController::class, 'crreate'])->name('subject.professor.create');
});
