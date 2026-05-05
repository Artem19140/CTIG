<?php

namespace App\Http\Controllers\Web\User;

use App\Domain\User\CreateUserAction;
use App\Domain\User\UpdateUserAction;
use App\Enums\UserRoles;
use App\Exceptions\BusinessException;
use App\Http\Requests\User\UserPostRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use Inertia\Inertia;

class UserController{

    public function show(User $user){
        return new UserResource($user);
    }

    public function index(){
        $users = User::active()
            ->with(['roles'])
            ->orderBy('surname')
            //уволенных тоже
            ->get();
        return Inertia::render('Center/Center', [
            'employees' => UserResource::collection($users),
            'tab' => 'employees'
        ]);
    }

    public function store(UserPostRequest $request, CreateUserAction $createUser){
        if(!$request->user()->isOrgAdmin() && !$request->user()->isSuperAdmin()){
            abort(403);
        }
        $createUser->execute($request->validated(), $request->user());
        return response()->json();
    }

    public function update(
        UserUpdateRequest $request,
        User $user,
        UpdateUserAction $updateUserAction
    ){
        if($user->isSuperAdmin()){
            abort(403);
        }
        $updateUserAction->execute($request->validated(),  $user);
        return response()->json();
    }

    public function destroy(User $user, Request $request){
        if(!$request->user()->isOrgAdmin() && !$request->user()->isSuperAdmin()){
            abort(403);
        }

        if($request->user()->center_id !== $user->center_id ){
            abort(403);
        }

        if(!$user->is_active){
            throw new BusinessException('Сотрудник уже уволен');
        }
        
        $user->is_active = false;
        
        $user->save();
        return response()->noContent();
    }

    public function rolesShow(Request $request){
        return RoleResource::collection(
            Role::select(['id', 'name'])
                ->when(!$request->user()->isSuperAdmin(), function (Builder $query){
                    $query->where('name','<>', UserRoles::SuperAdmin)
                        ->where('name','<>', UserRoles::OrgAdmin);
                })
                ->get()
        );
    }
}