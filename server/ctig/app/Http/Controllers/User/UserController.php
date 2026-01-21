<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use App\Exceptions\DomainException;


class UserController extends Controller{

    public function show(User $user){
        return new UserResource($user);
    }

    public function index(){
        return UserResource::collection(User::all());
    }

    public function store(Request $request):JsonResponse{
        $user = User::where("email", $request->input('email'))->first();

        if($user){
            throw new DomainException("Пользователь с таким email уже существует");
        }

        User::create(request()->all());
        return response()->json(["result" => "ok"]);
    }
}