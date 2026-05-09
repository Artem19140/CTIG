<?php

namespace App\Exceptions\Attempt;

use App\Exceptions\BaseException;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class BaseAttemptException extends BaseException{
    protected $code = 400;

    public function render(Request $request){
        // if( Auth::guard('foreignNationals')->check()){
        //     Auth::guard('foreignNationals')->logout();
        //     $request->session()->invalidate();

        //     $request->session()->regenerateToken();
        // }
        
        if($request->expectsJson()){
            return response()->json([
                'message' => $this->message
            ], 400);
        }

        if(Auth::guard('web')->check()){
            if($request->inertia()){
                return Inertia::flash('error', $this->message)->back();
            }
            return back()->with(['error' => $this->message]);
        }
        
        if($request->inertia()){
            Inertia::flash(['error' => $this->message]);
            return Inertia::render('Auth/Login');
        }
        
        Inertia::flash('error', $this->message);
        return redirect()->route('login');
    }
}
