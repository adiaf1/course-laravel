<?php

use App\Http\Controllers\TutorialController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TutorialController::class, 'index'])->name('tutorial.index');

Route::get('/hari/{day}', [TutorialController::class, 'show'])
    ->whereNumber('day')
    ->name('tutorial.show');
