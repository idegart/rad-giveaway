<?php

namespace App\Services;

use App\Models\Code;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class LotteryService
{
    public function register(string $name, Code $code): Participant
    {
        $participant = new Participant();
        $participant->name = $name;
        $participant->code()->associate($code);
        $participant->date = now()->toDateString();
        $participant->save();

        return $participant;
    }

    /**
     * @return Participant|Model|null
     */
    public function winner(): ?Participant
    {
        return Participant::query()
            ->where('date', now()->toDateString())
            ->inRandomOrder()
            ->with('code')
            ->first();
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
}