<?php

use Aprog\Http\Controllers\ConsoleController;
use Illuminate\Support\Facades\Route;

$middleware = (array) config('aprog.console.middleware', []);

Route::middleware(array_merge(['web'], $middleware))->prefix('aprog/console')->group(function () {
    Route::get('/', [ConsoleController::class, 'index'])->name('console.index');
    Route::post('/run', [ConsoleController::class, 'run'])->name('console.run');
});
