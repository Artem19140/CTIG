<?php

namespace App\Http\Controllers\Web\Employee;

use App\Domain\Center\CenterContext;
use App\Domain\Employee\CreateEmployeeAction;
use App\Domain\Employee\UpdateEmployeeAction;
use App\Enums\EmployeeRole;
use App\Exceptions\BusinessException;
use App\Http\Requests\Employee\EmployeePostRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Resources\Employee\EmployeeResource;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EmployeeController{

    public function show(Employee $employee){
        return new EmployeeResource($employee);
    }

    public function index(){
        
        $employees = Employee::active()
            ->forCenter(app(CenterContext::class)->id())
            ->with(['roles'])
            ->orderBy('surname')
            ->get();
        Log::info('employees_view', []);
        return Inertia::render('Center/Center', [
            'employees' => EmployeeResource::collection($employees),
            'tab' => 'employees'
        ]);
    }

    public function store(EmployeePostRequest $request, CreateEmployeeAction $createEmployee){
        $createEmployee->execute($request->validated(), $request->user());
        return response()->json();
    }

    public function update(
        EmployeeUpdateRequest $request,
        Employee $employee,
        UpdateEmployeeAction $updateEmployeeAction
    ){
        if($employee->isSuperAdmin()){
            abort(403);
        }
        $updateEmployeeAction->execute($request->validated(),  $employee);
        return response()->json();
    }

    public function destroy(Employee $employee, Request $request){
        if($request->user()->center_id !== $employee->center_id && !$request->user()->isSuperAdmin()){
            abort(403);
        }
        
        if($employee->isSuperAdmin()){
            abort(403);
        }
        
        if($employee->isCenterAdmin() && !$request->user()->isSuperAdmin()){
            abort(403);
        }

        if(!$employee->isActive()){
            throw new BusinessException('Сотрудник уволен');
        }
        
        $employee->is_active = false;
        
        $employee->save();
        return response()->noContent();
    }

    public function rolesShow(Request $request){
        return RoleResource::collection(
            Role::select(['id', 'name'])
                ->when(!$request->user()->isSuperAdmin(), function (Builder $query){
                    $query->where('name','<>', EmployeeRole::SuperAdmin)
                        ->where('name','<>', EmployeeRole::CenterAdmin);
                })
                ->get()
        );
    }
}