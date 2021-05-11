<?php

namespace Tests\Feature;

use App\Models\Code;
use App\Models\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use WithFaker;
    use DatabaseMigrations;

    /** @test **/
    public function user_can_not_register_with_invalid_name(): void
    {
        $code = Code::factory()->create();

        $this->postJson(route('register'), [
            'code' => $code->code,
        ])
            ->assertJsonValidationErrors('name');
    }
    
    /** @test **/
    public function user_can_not_register_with_invalid_code(): void
    {
        $this->postJson(route('register'), [
            'name' => $this->faker->name,
            'code' => 'invalid code',
        ])
            ->assertJsonValidationErrors('code');
    }

    /** @test **/
    public function user_can_not_register_with_expired_code(): void
    {
        $expiredCode = Code::factory()->expired()->create();

        $this->postJson(route('register'), [
            'name' => $this->faker->name,
            'code' => $expiredCode->code,
        ])
            ->assertJsonValidationErrors('code');
    }

    /** @test **/
    public function user_can_register_with_valid_input(): void
    {
        $code = Code::factory()->create();

        $this->postJson(route('register'), [
            'name' => $name = $this->faker->name,
            'code' => $code->code,
        ])
            ->assertSuccessful();

        $this->assertDatabaseHas((new Participant())->getTable(), [
            'name' => $name,
            'code_id' => $code->getKey(),
        ]);
    }
}
