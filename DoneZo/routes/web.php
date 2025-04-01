<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ('poop');
});

use App\Http\Controllers\PomodoroController;

Route::get('/pomodoro', [PomodoroController::class, 'index'])->name('pomodoro.index');
Route::post('/pomodoro/update', [PomodoroController::class, 'updateSession'])->name('pomodoro.update');
