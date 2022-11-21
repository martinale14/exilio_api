<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('user', UserController::class)->only([
    'index', 'show', 'store'
]);

Route::resource('post', PostController::class)->only([
    'index', 'show', 'store'
]);

Route::resource('comment', CommentController::class)->only([
    'store'
]);

Route::put('addLike/{postId}', [PostController::class, 'addLike']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
});