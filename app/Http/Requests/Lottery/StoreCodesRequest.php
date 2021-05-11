<?php

namespace App\Http\Requests\Lottery;

use Illuminate\Foundation\Http\FormRequest;

class StoreCodesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:txt',
            ],
        ];
    }
}
