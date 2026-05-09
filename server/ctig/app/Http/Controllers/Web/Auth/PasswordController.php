<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordController
{
    public function reset(PasswordResetRequest $request, User $user){
        if($user->isSuperAdmin()){
            abort(404);
        }

        $wrongPassword = !Hash::check($request->validated('adminPassword'), $request->user()->password);

        if($wrongPassword){
            throw ValidationException::withMessages(['adminPassword'  => 'Неверные учетные данные']);
        }

        $user->password = Hash::make($request->validated('password'));
        $user->has_to_change_password = true;

        $user->save();
        return response()->json();
   }

    public function change(ChangePasswordRequest $request){
        $user = $request->user();
        $plainPassword = $request->validated('password');

        if(Hash::check($plainPassword, $user->password)){
            throw ValidationException::withMessages([
                'password' =>'Пароль должен отличаться от старого'
            ]);
        }

        $user->update([
            'password' => Hash::make($plainPassword),
            'has_to_change_password' => false
        ]);
        
        return redirect()->to($user->resolveRedirect());
    }
}
