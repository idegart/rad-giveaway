<?php

use App\Http\Controllers\LotteryController;

Route::get('participants', function () {
    return response()->json([
        'participants' => \App\Models\Participant::count(),
    ]);
});

Route::post('register', [LotteryController::class, 'register'])->name('register');
Route::post('winner', [LotteryController::class, 'winner'])->name('winner');
Route::post('delete-participants', [LotteryController::class, 'deleteParticipants'])->name('deleteParticipants');
Route::get('download-participants', [LotteryController::class, 'downloadParticipants'])->name('downloadParticipants');

//Route::post('codes', [LotteryController::class, 'storeCodes'])->name('storeCodes');
