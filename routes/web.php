<?php

use App\Http\Controllers\TutorialController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TutorialController::class, 'home'])->name('tutorial.home');

Route::get('/kursus/{slug}', [TutorialController::class, 'course'])
    ->name('tutorial.course');

Route::get('/kursus/{slug}/hari/{day}', [TutorialController::class, 'show'])
    ->whereNumber('day')
    ->name('tutorial.show');

Route::get('/hari/{day}', [TutorialController::class, 'legacyDayRedirect'])
    ->whereNumber('day');
