<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LogoutController
{
    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function logoutAll(Request $request){
        $request->validate(['password' => ['required']]);

        $password = $request->input('password');

        $passwordWrong = !Hash::check($password, $request->user()->password);
        
        if($passwordWrong){
            throw ValidationException::withMessages(['password'  => 'Неверные учетные данные']);
        }
        
        Auth::logoutOtherDevices($password);
        Log::info('logout_other_divices', []);
        
        return response()->noContent();
    }
}
