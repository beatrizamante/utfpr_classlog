<?php

header('Content-Type: application/json');

use App\Controllers\BlockController;
use App\Controllers\ClassRoomController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\SchedulesController;
use App\Controllers\SubjectController;
use App\Controllers\UsersController;
use App\Controllers\UserSubjectsController;
use Core\Router\Route;

Route::get('/', [HomeController::class, 'index'])
  ->name('root');
Route::get('/schedules/blocks-index', [HomeController::class, 'indexBlocks'])
  ->name('root.blocks');
Route::post('/register', [UsersController::class, 'register'])
  ->name('users.register');
Route::post('/login', [UsersController::class, 'login'])
  ->name('users.login');

Route::get('/blocks', [BlockController::class, 'index'])
  ->name('blocks');
Route::get('/classrooms', [ClassRoomController::class, 'index'])
  ->name('classroom');
Route::get('/classrooms/{id}', [ClassRoomController::class, 'show'])
  ->name('classroom.show');

Route::middleware('auth')->group(function () {

  Route::get('/user-subjects', [UserSubjectsController::class, 'index'])
    ->name('subject.professor');

  Route::get('/logout', [UsersController::class, 'destroy'])
    ->name('users.logout');
  Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
  Route::get('/users/professors', [UsersController::class, 'professors'])
    ->name('users.professors');

  Route::middleware('role:admin')->group(function () {
    Route::post('/blocks', [BlockController::class, 'create'])
      ->name('blocks.create');
    Route::post('/blocks/image-update/{id}', [BlockController::class, 'imageUpdate'])
      ->name('blocks.image');
    Route::put('/blocks/{id}', [BlockController::class, 'update'])
      ->name('blocks.update');
    Route::delete('/blocks/{id}', [BlockController::class, 'destroy'])
      ->name('blocks.destroy');

    Route::post('/classrooms', [ClassRoomController::class, 'create'])
      ->name('classroom.create');
    Route::put('/classrooms/{id}', [ClassRoomController::class, 'update'])
      ->name('classroom.update');
    Route::delete('/classrooms/{id}', [ClassRoomController::class, 'destroy'])
      ->name('classroom.destroy');

    Route::post('/subjects', [SubjectController::class, 'create'])
      ->name('subject.create');
    Route::put('/subjects/{id}', [SubjectController::class, 'update'])
      ->name('subject.update');
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])
      ->name('subject.destroy');

    Route::post('/user-subjects', [UserSubjectsController::class, 'addSubjectToProfessor'])
      ->name('subject.professor.create');
    Route::delete('/user-subjects/{id}', [UserSubjectsController::class, 'delete'])
      ->name('subject.professor.delete');

    Route::post('/schedules', [SchedulesController::class, 'create'])
      ->name('schedules.create');
    Route::delete('/schedules/{id}', [SchedulesController::class, 'delete'])
      ->name('schedules.delete');
  });

  // Outras rotas protegidas
  Route::get('/schedules', [SchedulesController::class, 'index'])
    ->name('schedules.index');

  Route::get('/schedules/professor/{id}', [SchedulesController::class, 'byProfessorId'])
    ->name('schedules.userId');

  Route::get('/schedules/{id}', [SchedulesController::class, 'show'])
    ->name('schedules.show');
  Route::post('/schedules/cancel', [SchedulesController::class, 'creatreCancelSchedule'])
    ->name('schedules.cancel');
  Route::delete('/schedules/cancel/{id}', [SchedulesController::class, 'deleteCancelSchedule'])
    ->name('schedules.cancel.delete');
  Route::post('/schedules/change', [SchedulesController::class, 'roomChange'])
    ->name('schedules.post');

  Route::get('/schedules/exceptions', [SchedulesController::class, 'exceptions'])
    ->name('schedules.exceptions');

  Route::get('/blocks/{id}', [BlockController::class, 'show'])
    ->name('blocks.show');

  Route::get('/subjects', [SubjectController::class, 'index'])
    ->name('subject');
  Route::get('/subjects/{id}', [SubjectController::class, 'show'])
    ->name('subject.show');
});

