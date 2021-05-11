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
        $participant->date = now();
        $participant->save();

        return $participant;
    }

    /**
     * @return Participant|Model|null
     */
    public function winner(): ?Participant
    {
        return Participant::query()
            ->where('date', now())
            ->inRandomOrder()
            ->with('code')
            ->first();
    }

    public function uploadCodes(UploadedFile $file)
    {
        $content = $file->getContent();
        $data = array_map(function (string $code) {
            return [
                'code' => $code,
                'valid_date' => now()->toDateString(),
            ];
        }, explode(PHP_EOL, $content));

        Code::query()->insert($data);
    }
}