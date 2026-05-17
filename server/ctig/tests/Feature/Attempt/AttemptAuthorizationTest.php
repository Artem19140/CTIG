<?php

namespace Tests\Feature\Attempt;

use App\Models\Attempt;
use App\Models\Center;
use App\Models\ForeignNational;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttemptAuthorizationTest extends TestCase
{
    use RefreshDatabase;
    public function test_fail_not_attempt_owner(): void{

        $center = Center::factory()->create();
        $foreignNational = ForeignNational::factory()
            ->create([
                'center_id' => $center->id
            ]);

        $attempt = Attempt::factory()
            ->create([
                'center_id' => $center->id
            ]);
    
        $response = $this->actingAs($foreignNational)
            ->get(route('attempts.show', [
                'attempt' => $attempt
            ]));

        $response->assertRedirectToRoute('login');
    }
}
