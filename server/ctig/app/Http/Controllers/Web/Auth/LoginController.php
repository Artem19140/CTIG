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

        $request->session()->regenerate();
        
        $user = Auth::user();

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
        return $wrongCredentials || !Auth::user()->isActive() ||  !Auth::user()->center->isActive();
    }

   
   
}
