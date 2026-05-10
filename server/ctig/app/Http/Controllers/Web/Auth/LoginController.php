<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;


class LoginController
{
   public function login(LoginRequest $request)
    {
        
        if ($this->noAccess($request)) { 
            throw ValidationException::withMessages([
                'email' => 'Неверные учетные данные.',
                'password' => ''
            ]);
        }

        $user = Auth::user();
        $request->session()->regenerate();
        $user->loadMissing('center');
        if(!$user->isActive() || !$user->center->isActive()){
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Неверные учетные данные.',
                'password' => ''
            ]);
        }

        if ($user->hasChangePassword()) {
            return redirect()->route('password.change');
        }

        return redirect()->to($user->resolveRedirect());
    }

    protected function noAccess(LoginRequest $request):bool{
        $wrongCredentials = !Auth::attempt([
            'email' => $request->validated('email'),
            'password' => $request->validated('password')
        ], $request->validated('rememberMe'));
        return $wrongCredentials;
    }

   
   
}
