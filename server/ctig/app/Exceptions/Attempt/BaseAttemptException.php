<?php

namespace App\Exceptions\Attempt;

use Exception;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Contracts\Debug\ShouldntReport;
use Log;

class BaseAttemptException extends Exception implements ShouldntReport{
    protected $code = 400;

    public function render(Request $request){
        if($request->expectsJson()){
            return response()->json([
                'message' => $this->message
            ], 400);
        }

        if( Auth::guard('foreignNationals')->check()){

            Auth::guard('foreignNationals')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            Inertia::flash('error', $this->message);

            return redirect()->route('login');
        }

        if(Auth::guard('web')->check()){
            return Inertia::flash('error', $this->message)->back();
        }
        
        Log::channel('single')->error('UNEXPECTED:BaseAttemptException reached render unexpectedly',[
            'message' => $this->message
        ]);
        abort(500);
    }
}
