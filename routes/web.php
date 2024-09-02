<?php

use App\Actions\ProfileAction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileAction::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileAction::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileAction::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
