<?php

use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\ThreadsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::post('/threads/{channelSlug}/{thread}/replies', [RepliesController::class, 'store'])->name('replies.store');
    Route::resource('/threads', ThreadsController::class)->except('index', 'show');
    Route::post('/replies/{reply}/favorites', [FavoritesController::class, 'store']);
});

Route::get('/threads', [ThreadsController::class, 'index']);
Route::get('/threads/{channelSlug}/{thread}', [ThreadsController::class, 'show']);
Route::get('/threads/{channel}', [ThreadsController::class, 'index']);
Route::get('/profiles/{user}', [ProfilesController::class, 'show'])->name('profile');




