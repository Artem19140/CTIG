<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestTimeMeasure
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = hrtime(true);

        $response = $next($request);

        $durationMs = (hrtime(true) - $start) / 1_000_000;        

        if($durationMs >= 500){
            Log::warning('long_request_handle', [
                'path' => $request->route()?->getName() ?? $request->path(),
                'duration_ms' => round($durationMs, 1),
                'url' => request()->url()
            ]);
        }
        return $response;
    }
}
