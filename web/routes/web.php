<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthRedirect;
use App\Http\Middleware\AuthCheck;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Resources\BookResource;

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


Route::middleware([AuthRedirect::class])
->group(function(){
    Route::match(['get','post'],'login',[UserController::class,'login'])->name('user.login');
    Route::match(['get','post'],'register',[UserController::class,'register'])->name('user.register');
});

Route::get('sign-out',[UserController::class,'signOut'])->name('user.sign-out');

Route::middleware([AuthCheck::class])
->group(function(){
    Route::get('',[DashboardController::class,'index'])->name('dashboard');
    Route::resource('authors',AuthorController::class)->except(['show','destroy']);
    Route::resource('books',BookController::class)->except(['show','destroy']);
    Route::get('books/{book}/delete',[BookController::class,'destroy'])->name('books.destroy');
});


