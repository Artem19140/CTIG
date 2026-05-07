<?php

namespace Tests\Feature\Address;

use App\Models\Center;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressCreateTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected Center $center;
    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->user = User::factory()->orgAdmin()->create(['center_id' => $this->center->id]);
        
        Carbon::setTestNow(
            Carbon::now()
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    public function test_success(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postJson(route('centers.addresses.store', ['center' => $this->center]), [
                'address' => fake()->streetAddress,
                'capacity' => 12,
                
            ]);

        $response->assertStatus(200);
    }

    public function test_fail_no_required_fields(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postJson(route('centers.addresses.store', ['center' => $this->center]), [
                'center' => $this->center
            ]);

        $response->assertUnprocessable();
    }

    public function test_fail_less_zero_capacity(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postJson(route('centers.addresses.store', ['center' => $this->center]), [
                'address' => fake()->streetAddress,
                'capacity' => -12,
                'center' => $this->center
            ]);

        $response->assertUnprocessable();
    }
}
