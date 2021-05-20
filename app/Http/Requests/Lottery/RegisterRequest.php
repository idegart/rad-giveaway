<?php

namespace App\Http\Requests\Lottery;

use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
            ],
            'phone' => [
                'required',
                'string',
            ],
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
                'required',
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

    public function messages(): array
    {
        return [
            'email.unique' => 'Вы уже зарегистрированы! Повторная регистрация невозможна.',
            'phone.unique' => 'Вы уже зарегистрированы! Повторная регистрация невозможна.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->participantExists()) {
                $validator->errors()->add('exists', 'Вы уже зарегистрированы! Повторная регистрация невозможна.');
            }

            if (!$this->dateValid()) {
                $validator->errors()->add('birthday', 'Регистрация участников не достигших 18 лет невозможна.');
            }
        });
    }

    public function participantExists(): bool
    {
        return Participant::query()
            ->where('email', $this->input('email'))
            ->orWhere('phone', $this->input('phone'))
            ->exists();
    }

    public function dateValid(): bool
    {
        return Carbon::create(
            $this->input('year'),
            $this->input('month'),
            $this->input('day'),
        )->lessThanOrEqualTo(Carbon::create(2003, 5, 21));
    }
}
