<?php

namespace Tests\Feature\Address;

use App\Models\Address;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $this->user = User::factory()->create();

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
                'address' => fake()->streetAddress
            ]);

        $response->assertStatus(200);
    }

    public function test_fail_has_exam(): void
    {
        $address = Address::factory()->has(Exam::factory(10))->create();
        $response = $this
            ->actingAs($this->user)
            ->patchJson(route('addresses.update', ['address'=> $address]),[
                'address' => fake()->streetAddress
            ]);

        $response->assertBadRequest();
    }

    public function test_fail_no_required_field_address(): void
    {
        $address = Address::factory()->create();
        $response = $this
            ->actingAs($this->user)
            ->patchJson(route('addresses.update', ['address'=> $address]),[
            ]);

        $response->assertUnprocessable();
    }
}
