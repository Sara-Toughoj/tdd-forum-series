<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BestRepliesController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\LockedThreadsController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\ThreadsController;
use App\Http\Controllers\UserAvatarController;
use App\Http\Controllers\UserNotificationsController;
use http\Client\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::post('/threads/{channel}/{thread}/replies', [RepliesController::class, 'store'])->name('replies.store');
    Route::post('/threads/{channel}/{thread}/subscriptions', [SubscriptionsController::class, 'store'])->name('subscriptions.store');
    Route::delete('/threads/{channel}/{thread}/subscriptions', [SubscriptionsController::class, 'delete'])->name('subscriptions.delete');
    Route::delete('/threads/{channel}/{thread}', [ThreadsController::class, 'destroy']);
    Route::patch('/threads/{channel}/{thread}', [ThreadsController::class, 'update']);
    Route::resource('/threads', ThreadsController::class)->except('index', 'show', 'delete');
    Route::post('/threads', [ThreadsController::class, 'store'])->name('threads')->middleware(['verified']);
    Route::post('/replies/{reply}/favorites', [FavoritesController::class, 'store']);
    Route::delete('/replies/{reply}/favorites', [FavoritesController::class, 'destroy']);
    Route::post('/replies/{reply}/best', [BestRepliesController::class, 'store'])->name('best-replies.store');
    Route::delete('/replies/{reply}', [RepliesController::class, 'destroy'])->name('replies.destroy');
    Route::patch('/replies/{reply}', [RepliesController::class, 'update']);
    Route::delete('/profiles/{user}/notifications/{notification}', [UserNotificationsController::class, 'destroy'])->name('notifications.delete');
    Route::get('/profiles/{user}/notifications', [UserNotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/api/users/{user}/avatar', [UserAvatarController::class, 'store'])->name('avatar');
    Route::post('/locked-threads/{thread}', [LockedThreadsController::class, 'store'])->name('locked-threads.store')->middleware('admin');
    Route::delete('/locked-threads/{thread}', [LockedThreadsController::class, 'destroy'])->name('locked-threads.destroy')->middleware('admin');
});

Route::get('/', [ThreadsController::class, 'index']);
Route::get('/threads/{channel}/{thread}/replies', [RepliesController::class, 'index'])->name('replies.store');
Route::get('/threads/{channel}/{thread}', [ThreadsController::class, 'show']);
Route::get('/threads/{channel}', [ThreadsController::class, 'index']);
Route::get('/profiles/{user}', [ProfilesController::class, 'show'])->name('profile');
Route::get('/api/users', [UserController::class, 'index'])->name('user.index');

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');






