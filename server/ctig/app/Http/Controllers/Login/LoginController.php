<?php

namespace App\Http\Controllers\Login;

use App\Exceptions\ForbiddenException;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
   public function login(Request $request){
       $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api')->plainTextToken;
            return (new UserResource($user))
                        ->additional([
                            'token' => $token
                        ]);
        }

        throw new ForbiddenException('Неверный логин или пароль');
   }
}
