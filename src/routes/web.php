<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaygroundController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [PlaygroundController::class, 'dashboard'])
        ->name('dashboard');


    //Route::get('/play', [PlaygroundController::class, 'index'])
      //  ->name('play.index');

    Route::post('/play/run', [PlaygroundController::class, 'run'])
        ->name('play.run');

    Route::post('/snippets/{snippet}/favorite', [PlaygroundController::class, 'toggleFavorite'])
        ->name('play.favorite');

    Route::delete('/snippets/{snippet}', [PlaygroundController::class, 'destroy'])
        ->name('play.destroy');

    Route::put('/snippets/{snippet}', [PlaygroundController::class, 'update'])
        ->name('play.update');
});

require __DIR__ . '/auth.php';
