<?php

namespace App\Http\Requests\Lottery;

use App\Models\Code;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
            ],
            'code' => [
                'bail',
                'required',
                Rule::exists((new Code)->getTable(), 'code')
                    ->where('valid_date', now()->toDateString()),
                function ($attribute, $value, $fail) {
                    $code = $this->code();

                    if (Participant::query()->where('code_id', $code->getKey())->exists()) {
                        return $fail('Данный код уже зарегестрирован');
                    }

                    return null;
                },
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'code.exists' => 'Код не существует'
        ];
    }

    public function name(): string
    {
        return $this->input('name');
    }

    /**
     * @return Builder|Model
     */
    public function code(): Code
    {
        return Code::query()
            ->where('code', $this->input('code'))
            ->where('valid_date', now()->toDateString())
            ->firstOrFail();
    }
}
