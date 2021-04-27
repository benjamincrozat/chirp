<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MutedController;
use App\Http\Controllers\BlockedController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FollowersController;
use App\Http\Controllers\ListLikesController;
use App\Http\Controllers\DeleteUserController;
use App\Http\Controllers\FollowingsController;
use App\Http\Controllers\SearchLikesController;
use App\Http\Controllers\Auth\SocialiteController;

Route::get('/', HomeController::class)->name('home');
Route::get('/blocked', BlockedController::class)->name('blocked');
Route::get('/followers', FollowersController::class)->name('followers');
Route::get('/followings', FollowingsController::class)->name('followings');
Route::get('/likes', ListLikesController::class)->name('likes.index');
Route::get('/likes/search', SearchLikesController::class)->name('likes.search');
Route::get('/login', [SocialiteController::class, 'redirectToProvider'])->name('login');
Route::get('/login/callback', [SocialiteController::class, 'handleProviderCallback'])->name('login.callback');
Route::post('/logout', [SocialiteController::class, 'logout'])->name('logout');
Route::get('/muted', MutedController::class)->name('muted');
Route::get('/overview', OverviewController::class)->name('overview');
Route::get('/settings', SettingsController::class)->name('settings');
Route::delete('/user', DeleteUserController::class)->name('user.delete');
