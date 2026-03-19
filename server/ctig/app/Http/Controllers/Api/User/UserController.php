<?php


namespace App\Http\Controllers\Api\User;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\User\UserPostRequest;
use Hash;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;


class UserController extends Controller{

    public function show(User $user){
        return new UserResource($user);
    }

    public function index(){
        return UserResource::collection(User::all());
    }

    public function store(UserPostRequest $request):JsonResponse{
        $user = User::where("email", $request->input('email'))->first();

        if($user){
            throw new BusinessException("Пользователь с таким email уже существует");
        }

        $user = User::create([
            'email' => $request->validated('email'),
            'name' => $request->validated('name'),
            'surname' => $request->validated('surname'),
            'patronymic' => $request->validated('patronymic'),
            'job_title' => $request->validated('jobTitle'),
            'password' => Hash::make($request->validated('password')),
            'organization_id' => 1
        ]);
        $user->roles()->sync($request->validated('roles'));
        return response()->json(["result" => "ok"]);
    }
}