<?php


namespace App\Http\Controllers\Web\User;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\User\UserPostRequest;
use App\Models\User;
use Hash;
use App\Http\Resources\User\UserResource;
use Inertia\Inertia;


class UserController extends Controller{

    public function show(User $user){
        return new UserResource($user);
    }

    public function index(){
        return Inertia::render('Employees/Employees', [
            'employees' => UserResource::collection(User::paginate())
        ]);
    }

    public function store(UserPostRequest $request){
        $user = User::where("email", $request->input('email'))->first();

        if($user){
            throw new BusinessException("Пользователь с таким email уже существует");
        }

        User::create([
            'email' => $request->validated('email'),
            'name' => $request->validated('name'),
            'surname' => $request->validated('surname'),
            'patronymic' => $request->validated('patronymic'),
            'job_title' => $request->validated('jobTitle'),
            'password' => Hash::make($request->validated('password')),
            'organization_id' => $request->user()->organization_id
        ]);
        return back()->with('success', 'Сотрудник добавлен');
    }

    public function destroy(User $user){
        if(!$user->is_work){
            throw new BusinessException('Сотрудник уже удален');
        }
        $user->is_work = false;
        $user->save();
        return back()->with('success', 'Сотрудник удален');
    }
}