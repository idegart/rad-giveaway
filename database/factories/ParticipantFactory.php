<?php

namespace Database\Factories;

use App\Models\Code;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipantFactory extends Factory
{
    protected $model = Participant::class;

    public function definition(): array
    {
        return [
            'code_id' => Code::factory(),
            'name' => $this->faker->name,
            'date' => now(),
        ];
    }

    public function expired(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => now()->subDay(),
            ];
        });
    }
}
