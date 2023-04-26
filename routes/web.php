<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/movies', [App\Http\Controllers\MovieController::class, 'index']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/movie', [App\Http\Controllers\MovieController::class, 'store']);
    Route::post('/rateMovie', [App\Http\Controllers\MovieController::class, 'rateMovie']);
});


