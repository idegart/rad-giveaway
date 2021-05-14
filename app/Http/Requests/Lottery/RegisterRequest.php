<?php

namespace App\Http\Requests\Lottery;

use App\Models\Participant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'surname' => [
                'required',
                'string',
                'max:255',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'patronymic' => [
                'nullable',
                'string',
                'max:255',
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

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->notUniqueName()) {
                $validator->errors()->add('full_name', 'Данный участник уже зарегестрирован');
            }
        });
    }

    public function notUniqueName(): bool
    {
        return Participant::query()
            ->where('surname', $this->input('surname'))
            ->where('name', $this->input('name'))
            ->where('patronymic', $this->input('patronymic'))
            ->exists();
    }
}
