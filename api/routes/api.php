<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Middleware\JWTVerify;

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

Route::post('login',[UserController::class,'login'])->name('login');
Route::post('register',[UserController::class,'register'])->name('register');

Route::get('update-book-price',[BookController::class,'updatePrice'])->name('update-book-price');

Route::middleware([JWTVerify::class])
    ->group(function(){
        Route::apiResource('authors',AuthorController::class)->only(['index','show','store','update']);
        Route::apiResource('books',BookController::class);
    });