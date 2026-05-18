<?php

namespace Tests\Feature\ForeignNational;

use App\Models\Center;
use App\Models\Employee;
use App\Models\ForeignNational;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForeignNationalPolicyTest extends TestCase
{
    use RefreshDatabase;  
    protected Center $center;
    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();
    }
    public function test_operator_base_access_permissions(): void
    {
        $operator = Employee::factory()->operator()->create([
            'center_id' => $this->center->id
        ]);
        $foriegnNational = ForeignNational::factory()->create([
            'center_id' => $this->center->id
        ]);
        $this->assertTrue($operator->can('viewAny', ForeignNational::class),
            'opearator should viewAny FN');
        $this->assertTrue($operator->can('create', ForeignNational::class),
            'opearator create viewAny FN');
        $this->assertTrue($operator->can('update', $foriegnNational),
            'opearator update viewAny FN');
        $this->assertTrue($operator->can('files', $foriegnNational),
            'opearator files viewAny FN');
        $this->assertFalse($operator->can('export', ForeignNational::class),
            'opearator should not export FN');
    }

    public function test_export_access_permissions(): void
    {
        $director = Employee::factory()->director()->create([
            'center_id' => $this->center->id
        ]);
        $this->assertTrue($director->can('export', ForeignNational::class),
            'opearator should viewAny FN');
        
    }
}
