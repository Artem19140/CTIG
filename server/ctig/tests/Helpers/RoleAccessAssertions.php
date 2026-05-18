<?php

namespace Tests\Helpers;

use App\Enums\EmployeeRole;
use App\Models\Center;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

trait RoleAccessAssertions{
    public function assertRoleCan(
        EmployeeRole $role, 
        string $ability, 
        Model | string  $model, 
        bool $expected,
        Center | null $center = null
    ){
        $employee = Employee::factory()
            ->withRole($role)
            ->create(
                $center ? ['center_id' => $center->id] : []
            );

        $this->assertSame(
            $expected,
            $employee->can($ability, $model),
            "role {$role->value} failed on {$ability}"
        );
    }
}