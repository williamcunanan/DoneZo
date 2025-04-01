<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ('poop');
});

use App\Http\Controllers\PomodoroController;

Route::get('/pomodoro', [PomodoroController::class, 'index'])->name('pomodoro.index');
Route::post('/pomodoro/update', [PomodoroController::class, 'updateSession'])->name('pomodoro.update');

use App\Http\Controllers\TaskController;

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

