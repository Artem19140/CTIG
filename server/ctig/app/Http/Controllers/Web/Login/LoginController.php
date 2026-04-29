<?php

namespace App\Http\Controllers\Web\Login;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class LoginController
{
   public function login(LoginRequest $request)
    {
        if ($this->noAccess($request)) { 
            throw ValidationException::withMessages([
                'email' => 'Неверные учетные данные.',
            ]);
        }

        $request->session()->regenerate();
        
        $user = Auth::user();

        if ($user->hasChangePassword()) {
            return redirect()->route('password.change');
        }

        return redirect()->route('exams.index');
    }

    protected function noAccess(LoginRequest $request):bool{
        $wrongCredentials = !Auth::attempt([
            'email' => $request->validated('email'),
            'password' => $request->validated('password')
        ], $request->validated('rememberMe'));
        return $wrongCredentials || !Auth::user()->isActive() ||  !Auth::user()->center->isActive();
    }

   public function changePassword(ChangePasswordRequest $request){
        $user = $request->user();

        if(!$user->has_to_change_password){
            abort(403);
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

   public function resetPassword(PasswordResetRequest $request, User $user){
        $wrongPassword = !Hash::check($request->validated('adminPassword'), $request->user()->password);
        if($wrongPassword){
            throw ValidationException::withMessages(['adminPassword'  => 'Неверные учетные данные']);
        }

        $user->password = Hash::make($request->validated('password'));
        $user->has_to_change_password = true;
        $user->save();
        return response()->json();
   }
}
