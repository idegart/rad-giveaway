<?php

namespace Tests\Feature;

use App\Models\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WinnerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test **/
    public function can_get_result_when_no_winner(): void
    {
        $this->postJson(route('winner'))
            ->assertSuccessful()
            ->assertExactJson([
                'success' => false,
            ]);
    }

    /** @test **/
    public function not_winner_not_in_current_day(): void
    {
        Participant::factory()->count(10)->expired()->create();

        $this->postJson(route('winner'))
            ->assertSuccessful()
            ->assertExactJson([
                'success' => false,
            ]);
    }

    /** @test **/
    public function can_get_winner(): void
    {
        Participant::factory()->count(10)->create();

        $this->postJson(route('winner'))
            ->assertSuccessful()
            ->assertJsonMissingExact([
                'success' => false,
            ])
            ->assertJsonStructure([
                'data'
            ]);
    }
}
