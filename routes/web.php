<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TalentController;
use App\Http\Controllers\UserTransactionController;

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

// Homepage
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Dashboard
Route::prefix('dashboard')
    ->middleware(['auth:sanctum','admin'])
    ->group(function() {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::resource('marketplace/talents', TalentController::class);
        Route::get('users/talents', [UserController::class, 'indexOfTalents'])->name('users.talents');
        Route::get('users/partners', [UserController::class, 'indexOfPartners'])->name('users.partners');
        Route::get('users/new-requests', [UserController::class, 'newAccountRequest'])->name('users.new-requests');
        Route::resource('users', UserController::class);

        // Route::get('transactions/{id}/status/{status}', [TransactionController::class, 'changeStatus'])
        //     ->name('transactions.changeStatus');
        Route::resource('transactions', UserTransactionController::class);
    });

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
