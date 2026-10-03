<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [ProductController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Routes voor ingelogde gebruikers
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Magazijn overzicht
    |--------------------------------------------------------------------------
    */

    Route::get('/producten', [ProductController::class, 'index'])
        ->name('producten.index');


    /*
    |--------------------------------------------------------------------------
    | Leverantie informatie
    |--------------------------------------------------------------------------
    */

    Route::get('/producten/{id}/levering-info', [ProductController::class, 'leveringInfo'])
        ->name('producten.leveringInfo');


    /*
    |--------------------------------------------------------------------------
    | Allergenen informatie
    |--------------------------------------------------------------------------
    */

    Route::get('/producten/{id}/allergenen-info', [ProductController::class, 'allergenenInfo'])
        ->name('producten.allergenenInfo');
});


/*
|--------------------------------------------------------------------------
| Authentication routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';