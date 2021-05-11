<?php

namespace App\Http\Requests\Lottery;

use Illuminate\Foundation\Http\FormRequest;

class WinnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
