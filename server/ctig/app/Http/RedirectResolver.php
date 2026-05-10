<?php

namespace App\Http;

use App\Domain\Attempt\Guard\AttemptGuard;
use App\Models\ForeignNational;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class RedirectResolver{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(): RedirectResponse|string{
        if(Auth::guard('web')->check()){
            $user = Auth::guard('web')->user();
            return redirect()->to($user->resolveRedirect());
        }

        if(Auth::guard('foreignNationals')->check()){
            $foreignNational = Auth::guard('foreignNationals')->user();
            return $this->resolveRedirectForeingnNational($foreignNational);
        }
        abort(404);
    }

    protected function resolveRedirectForeingnNational(ForeignNational $foreignNational){
        $attempt = $foreignNational->latestAttempt;

        if(!$attempt){
            Auth::guard('foreignNationals')->logout();
            return route('login');
        }

        $this->attemptGuard->ensureAccessible($attempt);
        
        if($attempt->isPending()){
            
            return redirect()->route('attempts.preparing', ['attempt' => $attempt]);
        }

        return redirect()->route('attempts.show', ['attempt' => $attempt]);
    }
}