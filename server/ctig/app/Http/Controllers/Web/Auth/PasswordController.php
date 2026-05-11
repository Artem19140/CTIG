<?php

namespace App\Http\Controllers\Web\Auth;

use App\Enums\Event;
use App\Enums\Resource;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Models\User;
use App\Support\Log\LogActivity;
use Illuminate\Support\Facades\Auth;
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
        $this->log(Event::PasswordReseted, [
            'user_id' => $user->id,
            'who_reset_password_id' => $request->user()->id
        ]);

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
        Auth::logoutOtherDevices($plainPassword);
        $this->log(Event::PasswordChanged, [
            'user_id' => $user->id
        ]);
        return redirect()->to($user->resolveRedirect());
    }

    protected function log(Event $event, array $context){
        LogActivity::event(
            event: $event,
            resource: Resource::User,
            context: $context
        );
    }
}
