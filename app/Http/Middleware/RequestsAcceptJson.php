<?php

namespace App\Http\Middleware;

use Closure;

class RequestsAcceptJson
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $acceptHeader = strtolower($request->headers->get('accept'));
        // If the accept header is not set to application/json
        // We attach it and continue the request
        if ($acceptHeader !== 'application/json') {
            $request->headers->set('Accept', 'application/json');
        }
        return $next($request);
    }
}
