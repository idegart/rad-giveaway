<?php

namespace App\Http\Requests\Lottery;

use App\Models\Code;
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
                'required',
                Rule::exists((new Code)->getTable(), 'code')
                    ->where('valid_date', now()),
            ]
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
            ->where('valid_date', now())
            ->firstOrFail();
    }
}
