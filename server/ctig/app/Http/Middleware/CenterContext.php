<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CenterContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()->isSuperAdmin()){
            context(['center_id' => null]);
            return $next($request);
        }

        context(['center_id' => $request->user()->center_id]);

        return $next($request);
    }
}
