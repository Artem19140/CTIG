<?php

namespace Tests\Helpers;

use App\Enums\EmployeeRole;
use App\Models\Center;
use App\Models\Employee;

trait RolesAccessCheck
{
    public function accessRolesCheck(
        array $allowedRoles,
        string $method,
        string | callable $route,
        array|callable $data = [],
        int $expectedCode = 200,
        bool $json = true,
        Center | null $center = null
    ){
        $this->accessCheck(
            roles:array_merge($allowedRoles, [EmployeeRole::SuperAdmin]),
            method:$method,
            route:$route,
            data:$data,
            expectedCode:$expectedCode,
            json:$json,
            center:$center
        );

        $this->accessCheck(
            roles:EmployeeRole::except( EmployeeRole::SuperAdmin, ...$allowedRoles),
            method:$method,
            route:$route,
            data:$data,
            expectedCode:403,
            json:$json,
            center:$center
        );
    }
    public function accessCheck(
        array $roles,
        string $method,
        string | callable $route,
        array|callable $data = [],
        int $expectedCode = 200,
        bool $json = true,
        Center | null $center = null
    ): void {
        if(empty($roles)){        
            return ;
        }
        
        foreach ($roles as $role) {
            $user = Employee::factory()
                ->withRole($role)
                ->create($center ? ['center_id' => $center-> id] : []);

            $payload = is_callable($data)
                ? $data($role, $user)
                : $data;

            $resolvedRoute = is_callable($route)
                ? $route($role, $user)
                : $route;

            $request = $this->actingAs($user);

            $response = match (strtoupper($method)) {
                'GET' => $json
                    ? $request->getJson($resolvedRoute)
                    : $request->get($resolvedRoute),

                'POST' => $json
                    ? $request->postJson($resolvedRoute, $payload)
                    : $request->post($resolvedRoute, $payload),

                'PUT' => $json
                    ? $request->putJson($resolvedRoute, $payload)
                    : $request->put($resolvedRoute, $payload),

                'PATCH' => $json
                    ? $request->patchJson($resolvedRoute, $payload)
                    : $request->patch($resolvedRoute, $payload),

                'DELETE' => $json
                    ? $request->deleteJson($resolvedRoute, $payload)
                    : $request->delete($resolvedRoute, $payload),

                default => throw new \InvalidArgumentException(
                    "Unsupported method: {$method}"
                ),
            };

            $response->assertStatus($expectedCode);
        }
    }
}