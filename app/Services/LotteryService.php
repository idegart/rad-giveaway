<?php

namespace App\Services;

use App\Models\Code;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Storage;

class LotteryService
{
    public function register(string $email, string $phone, string $surname, string $name, ?string $patronymic, int $day, int $month, int $year): Participant
    {
        $participant = new Participant();
        $participant->email = $email;
        $participant->phone = $phone;
        $participant->surname = $surname;
        $participant->name = $name;
        $participant->patronymic = $patronymic;
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
        $participant = Participant::query()->whereNull('winner_at')->inRandomOrder()->first();

        if ($participant) {
            $participant->winner_at = now();
            $participant->save();
        }

        return $participant;
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

    public function downloadParticipants()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->setCellValue('A1', 'Email');
        $worksheet->setCellValue('B1', 'Телефон');
        $worksheet->setCellValue('C1', 'ФИО');
        $worksheet->setCellValue('D1', 'Дата рождения');
        $worksheet->setCellValue('E1', 'Дата выигрыша');
        $worksheet->setCellValue('F1', 'Дата создания');

        foreach (Participant::get() as $key => $participant) {
            $subkey = $key + 2;
            $worksheet->setCellValue("A{$subkey}", $participant->email);
            $worksheet->setCellValue("B{$subkey}", $participant->phone);
            $worksheet->setCellValue("C{$subkey}", $participant->full_name);
            $worksheet->setCellValue("D{$subkey}", $participant->birthday);
            $worksheet->setCellValue("E{$subkey}", $participant->winner_at);
            $worksheet->setCellValue("F{$subkey}", $participant->created_at->toDateString());
        }

        $filename = "participants.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        Storage::disk('public')->put($filename, $content);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return Storage::url($filename);
    }
}