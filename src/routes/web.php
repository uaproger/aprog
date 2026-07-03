<?php

use Aprog\Http\Controllers\ConsoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->prefix('aprog/console')->group(function () {
    Route::get('/', [ConsoleController::class, 'index'])->name('console.index');
    Route::post('/run', [ConsoleController::class, 'run'])->name('console.run');
});
