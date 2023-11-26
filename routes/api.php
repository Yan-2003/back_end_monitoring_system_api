<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DestinationController;
use App\Http\Controllers\API\FareController;
use App\Http\Controllers\API\HinoController;
use App\Http\Controllers\API\PassengerController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


/* Private APIs */
Route::middleware('auth:sanctum')->group(function(){


    Route::get('/users' , [UserController::class , 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(HinoController::class)->group(function(){
        Route::get('/hino' , 'index');
        Route::post('/hino' , 'store');
        Route::delete('/hino/{id}' , 'destroy');
    });

    Route::controller(DestinationController::class)->group(function(){
        Route::get('/destinations' ,'index');
        Route::post('/destinations', 'store');
    });

    Route::controller(FareController::class)->group(function(){
        Route::get('/fare', 'index');
        Route::post('/fare', 'store');
    });


    Route::controller(TransactionController::class)->group(function(){
        Route::get('/transactions', 'index');
        Route::post('/transactions', 'store');
    });


    Route::controller(PassengerController::class)->group((function(){
        Route::get('/passengers', 'index');
        Route::post('/passengers' , 'store');
    }));



});



 /* Public APIs */
Route::post('/login' , [AuthController::class , 'login'])->name('user.login');


/* Creating user */
Route::post('/users' , [UserController::class , 'store']);