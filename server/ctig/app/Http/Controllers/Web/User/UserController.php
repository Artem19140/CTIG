<?php


namespace App\Http\Controllers\Web\User;

use App\Domain\User\CreateUserAction;
use App\Enums\UserRoles;
use App\Exceptions\BusinessException;
use App\Http\Requests\User\UserPostRequest;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use Inertia\Inertia;


class UserController{

    public function show(User $user){
        return new UserResource($user);
    }

    public function index(){
        return Inertia::render('Employees/Employees', [
            'employees' => UserResource::collection(User::paginate(10))
        ]);
    }

    public function store(UserPostRequest $request, CreateUserAction $createUser){
        $createUser->execute($request->validated(), $request->user());
        return Inertia::flash('success', 'Сотрудник добавлен')->back();
    }

    public function destroy(User $user, Request $request){
        if(!$user->is_active){
            throw new BusinessException('Сотрудник уже уволен');
        }
        if(
            $request->user()->center_id !== $user->center_id 
                || 
            !$request->user()->isSuperAdmin()
        ){
            abort(404);
        }
        $user->is_active = false;
        $user->save();
        Inertia::flash('success', 'Сотрудник уволен');
        return back();
    }

    public function rolesShow(){
        return RoleResource::collection(
            Role::select(['id', 'name'])
                ->where('name','<>', UserRoles::SuperAdmin)
                ->where('name','<>', UserRoles::OrgAdmin)
                ->get()
        );
    }
}