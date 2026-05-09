<?php

namespace Tests\Feature\Address;

use App\Models\Address;
use App\Models\Center;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressDeleteTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected Center $center;
    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->user = User::factory()->orgAdmin()->create([
            'center_id' => $this->center->id
        ]);
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
        $address = Address::factory()->active()->create(['center_id' => $this->center->id]);
        $response = $this
            ->actingAs($this->user)
            ->deleteJson(route('centers.addresses.destroy', ['address'=> $address , 'center' => $this->center]),[
                'center' => $this->center
            ]);
        $response->assertNoContent();
        $address->refresh();
        $this->assertFalse($address->is_active);
    }

}
