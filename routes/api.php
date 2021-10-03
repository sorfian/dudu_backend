<?php

use App\Http\Controllers\API\TalentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserTransactionController;
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

Route::middleware('auth:sanctum')->group(function(){
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('user/photo', [UserController::class, 'updatePhoto']);
    Route::post('logout', [UserController::class, 'logout']);

    Route::get('user/transaction', [UserTransactionController::class, 'all']);
    Route::post('user/transaction/{id}', [UserTransactionController::class, 'update']);
    Route::post('checkout', [UserTransactionController::class, 'checkout']);
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::get('talent', [TalentController::class, 'all']);
