<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/producten', [ProductController::class, 'index'])
    ->name('producten.index');

Route::get('/producten/{id}/levering-info', [ProductController::class, 'leveringInfo'])
    ->name('producten.leveringInfo');

Route::get('/producten/{id}/allergenen-info', [ProductController::class, 'allergenenInfo'])
    ->name('producten.allergenenInfo');

require __DIR__.'/auth.php';
