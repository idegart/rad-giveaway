<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lottery\{RegisterRequest, StoreCodesRequest, WinnerRequest};
use App\Http\Resources\ParticipantResource;
use App\Services\LotteryService;
use Illuminate\Http\JsonResponse;

class LotteryController extends Controller
{
    public function register(RegisterRequest $request, LotteryService $lotteryService): JsonResponse
    {
        $lotteryService->register($request->name(), $request->code());

        return response()->json([
            'success' => true,
        ]);
    }

    public function winner(WinnerRequest $request, LotteryService $lotteryService)
    {
        $participant = $lotteryService->winner();

        if ($participant) {
            return new ParticipantResource($participant);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    public function storeCodes(StoreCodesRequest $request, LotteryService $lotteryService): JsonResponse
    {
        $lotteryService->uploadCodes($request->file('file'));

        return response()->json([
            'success' => true,
        ]);
    }
}
