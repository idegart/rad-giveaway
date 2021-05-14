<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    use HasFactory;

    protected $appends = [
        'full_name',
    ];

    public function code(): BelongsTo
    {
        return $this->belongsTo(Code::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->surname} {$this->name} {$this->patronymic}";
    }
}
