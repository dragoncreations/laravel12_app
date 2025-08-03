<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Models\Task;

Route::redirect('/', '/home');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tasks', TaskController::class);
});

Route::get('/task/{token}', [TaskController::class, 'share'])->name('task.share');

Route::post('/task/link/{id}', [TaskController::class, 'link'])->name('task.link');

Route::post('/task/addToCalendar/{id}', [TaskController::class, 'addToCalendar'])->name('task.addToCalendar');
