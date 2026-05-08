<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
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


// Patcher
Route::get('plist', [ApiController::class, 'plist'])->name('plist');
Route::get('data/{file}', [ApiController::class, 'data'])->name('data');
// Route::get('patcher/', [PageController::class, 'patcher'])->name('patcher');

Route::get('vending_history/{item_id}', [ApiController::class, 'vendingHistory'])->name('vendingHistory');