<?php

namespace Tests\Feature\Address;

use App\Models\Address;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
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
        $address = Address::factory()->create();
        $response = $this
            ->actingAs($this->user)
            ->patchJson(route('addresses.update', ['address'=> $address]),[
                'address' => fake()->streetAddress,
                'maxCapacity' => $address->max_capacity + 1
            ]);

        $response->assertStatus(200);
    }

    public function test_success_has_exam(): void
    {
        $address = Address::factory()->has(Exam::factory(10))->create();

        $oldAddress = $address->address;
        $newCapacity = $address->max_capacity + 1;

        $response = $this
            ->actingAs($this->user)
            ->patchJson(route('addresses.update', ['address'=> $address]),[
                'address' => fake()->streetAddress,
                'maxCapacity' => $newCapacity
            ]);
        $address->refresh();

        $this->assertEquals($oldAddress, $address->address);

        $this->assertEquals($newCapacity, $address->max_capacity);
        $response->assertOk();
    }

    public function test_fail_no_required_fields(): void
    {
        $address = Address::factory()->create();
        $response = $this
            ->actingAs($this->user)
            ->patchJson(route('addresses.update', ['address'=> $address]),[
            ]);

        $response->assertUnprocessable();
    }
}
