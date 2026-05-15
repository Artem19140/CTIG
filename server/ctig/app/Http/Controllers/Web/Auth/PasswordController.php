<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PasswordController
{
    public function reset(PasswordResetRequest $request, Employee $employee){
        if($employee->isSuperAdmin()){
            abort(403);
        }

        $wrongPassword = !Hash::check(
            $request->validated('adminPassword'), 
            $request->user()->password
        );

        if($wrongPassword){
            throw ValidationException::withMessages([
                'adminPassword'  => 'Неверные учетные данные'
            ]);
        }

        $employee->password = Hash::make($request->validated('password'));
        $employee->has_to_change_password = true;
        
        $employee->save();
        Log::info('password_reset', [
            'employee_id' => $employee->id
        ]);

        return response()->json();
   }

    public function change(ChangePasswordRequest $request){
        $employee = $request->user();

        if($employee->isSuperAdmin()){
            abort(403);
        }
        $plainPassword = $request->validated('password');
        if(Hash::check($plainPassword, $employee->password)){
            throw ValidationException::withMessages([
                'password' =>'Пароль должен отличаться от старого'
            ]);
        }

        $employee->update([
            'password' => Hash::make($plainPassword),
            'has_to_change_password' => false
        ]);
        Log::info('password_change', [
            'employee_id' => $employee->id
        ]);

        Auth::logoutOtherDevices($plainPassword);

        return redirect()->to($employee->resolveRedirect());
    }
}
