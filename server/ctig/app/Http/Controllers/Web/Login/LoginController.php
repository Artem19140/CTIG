<?php

namespace App\Http\Controllers\Web\Login;

use App\Enums\TokenAbilities;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\User\UserResource;
use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
   public function login(Request $request)
    {
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Неверные учетные данные.',
            ]);
        }
        
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->has_to_change_password) {
            return redirect()->route('password.change');
        }

        return redirect()->route('exams');
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
        $token = $user->createToken(
                                TokenAbilities::SystemAccess->value,
                                [TokenAbilities::SystemAccess->value],
                                now()->addDays(14))->plainTextToken;
            return (new UserResource($user))
                        ->additional([
                            'token' => $token
                        ]);
   }
}
