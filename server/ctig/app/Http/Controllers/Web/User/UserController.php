<?php


namespace App\Http\Controllers\Web\User;

use App\Enums\UserRoles;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\User\UserPostRequest;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use App\Http\Resources\User\UserResource;
use Inertia\Inertia;


class UserController extends Controller{

    public function show(User $user){
        return new UserResource($user);
    }

    public function index(){
        return Inertia::render('Employees/Employees', [
            'employees' => UserResource::collection(User::paginate(10))
        ]);
    }

    public function store(UserPostRequest $request){
        $user = User::where("email", $request->input('email'))->first();
        
        if($user){
            throw new BusinessException("Пользователь с таким email уже существует");
        }

        $superAdminRole = Role::where('name', UserRoles::SuperAdmin)->first();

        if(\in_array($superAdminRole->id,$request->validated('roles')) && !$request->user()->isSuperAdmin()){
            abort(404);
        }

        $user = User::create([
            'email' => $request->validated('email'),
            'name' => $request->validated('name'),
            'surname' => $request->validated('surname'),
            'patronymic' => $request->validated('patronymic'),
            'job_title' => $request->validated('jobTitle'),
            'password' => Hash::make($request->validated('password')),
            'center_id' => $request->user()->center_id
        ]);
        
        $user->roles()->sync($request->validated('roles'));
        Inertia::flash('success', 'Сотрудник добавлен');
        return back();
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