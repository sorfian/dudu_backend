<?php

use App\Http\Controllers\API\TalentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserTransactionController;
use App\Http\Controllers\API\XenditController;
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
    Route::get('user/transaction/talent', [UserTransactionController::class, 'talentTransactions']);
    Route::post('user/transaction/upload', [UserTransactionController::class, 'uploadVideo']);
    Route::post('user/transaction/{id}', [UserTransactionController::class, 'update']);
    Route::post('user/status/update', [UserTransactionController::class, 'updateStatus']);
    Route::post('checkout', [UserTransactionController::class, 'checkout']);
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::get('talent', [TalentController::class, 'all']);
Route::post('xendit/callback', [XenditController::class, 'callback']);
