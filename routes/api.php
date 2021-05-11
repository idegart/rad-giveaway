<?php

use App\Http\Controllers\LotteryController;

Route::post('register', [LotteryController::class, 'register'])->name('register');
Route::post('winner', [LotteryController::class, 'winner'])->name('winner');
Route::post('codes', [LotteryController::class, 'storeCodes'])->name('storeCodes');
