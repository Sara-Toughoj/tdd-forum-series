<?php

use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\ThreadsController;
use App\Http\Controllers\UserNotificationsController;
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
    Route::post('/threads/{channel}/{thread}/replies', [RepliesController::class, 'store'])->name('replies.store');
    Route::post('/threads/{channel}/{thread}/subscriptions', [SubscriptionsController::class, 'store'])->name('subscriptions.store');
    Route::delete('/threads/{channel}/{thread}/subscriptions', [SubscriptionsController::class, 'delete'])->name('subscriptions.delete');
    Route::delete('/threads/{channel}/{thread}', [ThreadsController::class, 'destroy']);
    Route::resource('/threads', ThreadsController::class)->except('index', 'show', 'delete');
    Route::post('/replies/{reply}/favorites', [FavoritesController::class, 'store']);
    Route::delete('/replies/{reply}/favorites', [FavoritesController::class, 'destroy']);
    Route::delete('/replies/{reply}', [RepliesController::class, 'destroy']);
    Route::patch('/replies/{reply}', [RepliesController::class, 'update']);
    Route::delete('/profiles/{user}/notifications/{notification}', [UserNotificationsController::class, 'destroy'])->name('notifications.delete');
    Route::get  ('/profiles/{user}/notifications', [UserNotificationsController::class, 'index'])->name('notifications.index');
});

Route::get('/threads/{channel}/{thread}/replies', [RepliesController::class, 'index'])->name('replies.store');
Route::get('/threads', [ThreadsController::class, 'index']);
Route::get('/threads/{channel}/{thread}', [ThreadsController::class, 'show']);
Route::get('/threads/{channel}', [ThreadsController::class, 'index']);
Route::get('/profiles/{user}', [ProfilesController::class, 'show'])->name('profile');





