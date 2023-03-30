<?php

use App\Http\Controllers\BookController;
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

Route::apiResource('books', BookController::class);//->only('index', 'show', 'store', 'update', 'destroy');
Route::patch('books/{id}/change-current-page', [BookController::class, 'changeCurrentPage3']);
Route::get('reading', [BookController::class, 'reading']);
