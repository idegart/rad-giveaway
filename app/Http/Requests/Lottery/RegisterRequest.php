<?php

namespace App\Http\Requests\Lottery;

use App\Models\Participant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique((new Participant)->getTable(), 'name')
                    ->where('day', $this->input('day'))
                    ->where('month', $this->input('month'))
                    ->where('year', $this->input('year'))
            ],
            'day' => [
                'required',
                'integer',
                'min:1',
                'max:31',
            ],
            'month' => [
                'required',
                'integer',
                'min:1',
                'max:12',
            ],
            'year' => [
                'required',
                'integer',
                'min:1950',
                'max:2003',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Данный участник уже зарегестрирован',
        ];
    }
}
