<?php

namespace App\Services;

use App\Models\Code;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class LotteryService
{
    public function register(string $name, int $day, int $month, int $year): Participant
    {
        $participant = new Participant();
        $participant->name = $name;
        $participant->day = $day;
        $participant->month = $month;
        $participant->year = $year;
        $participant->save();

        return $participant;
    }

    /**
     * @return Participant|Model|null
     */
    public function winner(): ?Participant
    {
        return Participant::query()->inRandomOrder()->first();
    }

    public function uploadCodes(UploadedFile $file): void
    {
        $content = $file->getContent();
        $data = array_map(static function (string $code) {
            return [
                'code' => $code,
                'valid_date' => now()->toDateString(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }, explode(PHP_EOL, $content));

        Code::query()->insert($data);
    }

    public function deleteParticipants(): bool
    {
        return Participant::query()->delete();
    }
}