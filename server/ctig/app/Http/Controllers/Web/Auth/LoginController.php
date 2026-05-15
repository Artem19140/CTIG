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

        $employee = Auth::user();
        $request->session()->regenerate();
        $employee->loadMissing('center');
        if(!$employee->isActive() || !$employee->center->isActive()){
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Неверные учетные данные.',
                'password' => ''
            ]);
        }

        if ($employee->hasChangePassword()) {
            return redirect()->route('password.change');
        }

        return redirect()->to($employee->resolveRedirect());
    }

    protected function noAccess(LoginRequest $request):bool{
        $wrongCredentials = !Auth::attempt([
            'email' => $request->validated('email'),
            'password' => $request->validated('password')
        ], $request->validated('rememberMe'));
        return $wrongCredentials;
    }

   
   
}
