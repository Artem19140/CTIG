<?php

namespace App\Http\Controllers\Login;

use App\Exceptions\BusinessException;
use App\Exceptions\ForbiddenException;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Hash;
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
            if($user->has_to_change_password){
                $user->tokens()->delete();
                $token = $user->createToken(
                    'password-change',
                    ['change-password'],
                    now()->addMinutes(10))->plainTextToken;
                return (new UserResource($user))
                            ->additional([
                                'token' => $token
                            ]);
            }
            $token = $user->createToken(
                            'api', 
                            ['access-system'],
                            now()->addDays(14))->plainTextToken;
            return (new UserResource($user))
                        ->additional([
                            'token' => $token
                        ]);
        }

        throw new ForbiddenException('Неверный логин или пароль');
   }

   public function changePassword(Request $request){
        $request->validate([
            'newPassword' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user = $request->user();
        
        $user->update([
            'password' => Hash::make($request->input('newPassword')),
            'has_to_change_password' => false
        ]);

        $user->currentAccessToken()->delete();
        $token = $user->createToken('api', ['access-system'],now()->addDays(14))->plainTextToken;
            return (new UserResource($user))
                        ->additional([
                            'token' => $token
                        ]);
   }
}
