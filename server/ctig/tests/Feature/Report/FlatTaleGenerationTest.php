<?php

namespace Tests\Feature\Report;

use App\Models\Attempt;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlatTaleGenerationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_success(): void
    {
        Attempt::factory(5)->checked()->passed()->create([
            'created_at' => Carbon::now()
        ]);
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
