<?php

namespace Tests\Feature\Address;

use App\Models\Address;
use App\Models\Center;
use App\Models\Exam;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressUpdateTest extends TestCase
{
    use RefreshDatabase;
    protected Employee $employee;
    protected Center $center;
    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
        $this->employee = Employee::factory()->orgAdmin()->create([
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
        $address = Address::factory()->create([
            'center_id' => $this->center->id
        ]);
        $response = $this
            ->actingAs($this->employee)
            ->patchJson(route('centers.addresses.update', ['address'=> $address,  'center' => $this->center]),[
                'address' => fake()->streetAddress,
                'maxCapacity' => $address->max_capacity + 1
            ]);

        $response->assertStatus(200);
    }

    public function test_fail_has_exam(): void
    {
        $centerId = $this->center->id;
        $address = Address::factory()
            ->has(Exam::factory(10)->state(function(array $attributes) use($centerId) {
                return [
                    'center_id' => $centerId
                ];
            }))->create([
                'center_id' => $this->center->id
            ]);

        $oldAddress = $address->address;
        $newCapacity = $address->max_capacity + 1;

        $response = $this
            ->actingAs($this->employee)
            ->patchJson(route('centers.addresses.update', ['address'=> $address,  'center' => $this->center]),[
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
        $address = Address::factory()->create([
            'center_id' => $this->center->id
        ]);
        $response = $this
            ->actingAs($this->employee)
            ->patchJson(route('centers.addresses.update', ['address'=> $address,  'center' => $this->center]),[
            ]);

        $response->assertUnprocessable();
    }
}
