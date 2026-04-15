<?php

namespace App\Http\Controllers\Web\Login;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use Hash;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class LoginController extends Controller
{
   public function login(LoginRequest $request)
    {
        if (!Auth::attempt([
                                'email' => $request->validated('email'),
                                'password' => $request->validated('password')
                            ], 
                            $request->validated('rememberMe')
            )) { 
            throw ValidationException::withMessages([
                'email' => 'Неверные учетные данные.',
            ]);
        }
        
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->has_to_change_password) {
            return redirect()->route('password.change');
        }

        return redirect()->route('exams.index');
    }

   public function changePassword(ChangePasswordRequest $request){
        $user = $request->user();
        if(!$user->has_to_change_password){
            abort(404, 'Не найдено');
        }
        if(Hash::check($request->validated('newPassword'), $user->password)){
            return ValidationException::withMessages([
                'newPassword' =>'Пароль должен отличаться от старого'
            ]);
        }
        $user->update([
            'password' => Hash::make($request->input('newPassword')),
            'has_to_change_password' => false
        ]);
        return redirect()->route('exams.index')->with('success', 'Пароль изменён!');
   }

   public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
   }
}
