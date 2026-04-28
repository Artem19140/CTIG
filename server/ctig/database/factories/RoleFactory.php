<?php

namespace Database\Factories;

use App\Enums\UserRoles;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(array_column(UserRoles::cases(), 'value'))
        ];
    }
    public function role(UserRoles $role){
        return $this->state(function() use($role){
            return [
                'name' => $role,
            ];
        });
    }

    public function superAdmin(){
        return $this->role(UserRoles::SuperAdmin);
    }

    public function orgAdmin(){
        return $this->role(UserRoles::OrgAdmin);
    }

    public function operator(){
        return $this->role(UserRoles::Operator);
    }

    public function director(){
        return $this->role(UserRoles::Director);
    }

    public function scheduler(){
        return $this->role(UserRoles::Scheduler);
    }

    public function examiner(){
        return $this->role(UserRoles::Examiner);
    }
}
