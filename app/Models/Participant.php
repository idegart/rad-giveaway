<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Participant extends Model
{
    use HasFactory;

    protected $appends = [
        'full_name',
        'birthday',
    ];

    public function code(): BelongsTo
    {
        return $this->belongsTo(Code::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->surname} {$this->name} {$this->patronymic}";
    }

    public function getBirthdayAttribute(): string
    {
        $day = Str::padLeft($this->day, 2, 0);
        $month = Str::padLeft($this->month, 2, 0);

        return "{$day}-{$month}-{$this->year}";
    }
}
