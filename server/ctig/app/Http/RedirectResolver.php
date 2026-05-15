<?php

namespace App\Http;

use Illuminate\Support\Facades\Auth;
use Log;


class RedirectResolver{
    public function execute():string{
        if(Auth::guard('web')->check()){
            $employee = Auth::guard('web')->user();
            return $employee->resolveRedirect();
        }

        if(Auth::guard('foreignNationals')->check()){
            $foreignNational = Auth::guard('foreignNationals')->user();
            $attempt = $foreignNational->latestAttempt;

            if(!$attempt){
                Log::warning('authorized foreiqn national without attempt', []);
                Auth::guard('foreignNationals')->logout();
                return route('login');
            }
            return route('attempts.show', ['attempt' => $attempt]);
        }

        Log::critical('UNEXPECTED: RedirectResolver end no resolve', []);
        
        abort(500);
    }
}