<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

class LogContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Context::add([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'agent' => $request->userAgent(),
            'url' => $request->url(),
            'user_type' =>$request->user()::class
        ]);
        return $next($request);
    }
}
