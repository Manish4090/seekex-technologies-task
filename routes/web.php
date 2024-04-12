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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/buckets/store', [App\Http\Controllers\BucketController::class, 'store'])->name('buckets.store')->middleware('auth');
Route::post('/balls/store', [App\Http\Controllers\BallController::class, 'store'])->name('balls.store')->middleware('auth');
Route::post('/bucket-suggestions', [App\Http\Controllers\BucketController::class, 'allocateBalls'])->name('bucket.suggestions')->middleware('auth');



