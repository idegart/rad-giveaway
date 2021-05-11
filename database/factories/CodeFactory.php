<?php

namespace Database\Factories;

use App\Models\Code;
use Illuminate\Database\Eloquent\Factories\Factory;

class CodeFactory extends Factory
{
    protected $model = Code::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->numberBetween(10000, 99999),
            'valid_date' => now(),
        ];
    }

    public function expired(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'valid_date' => now()->subDay(),
            ];
        });
    }
}
