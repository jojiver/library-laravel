<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\LibraryStatisticsController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::post('/admin/login', [AuthController::class, 'login']);

Route::get('/library/statistics', [LibraryStatisticsController::class, 'index']);

Route::apiResource('books', BookController::class);

Route::apiResource('borrowings', BorrowingController::class)->except(['update', 'destroy']);

Route::put('/borrowings/{id}/return', [BorrowingController::class, 'returnBook']);