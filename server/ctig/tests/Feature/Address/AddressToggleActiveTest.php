<?php

namespace Tests\Feature\Address;

use App\Models\Address;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressToggleActiveTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->orgAdmin()->create();
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
        $this->withoutExceptionHandling();
        $address = Address::factory()->notActive()->create();
        $response = $this
            ->actingAs($this->user)
            ->patchJson(route('addresses.toggle.activity', ['address'=> $address]),[
                'active' => !$address->is_active
            ]);
        $response->assertStatus(204);
        $this->assertFalse($address->is_active);
    }

}
