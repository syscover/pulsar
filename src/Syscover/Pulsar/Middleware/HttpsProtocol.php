<?php namespace Syscover\Pulsar\Middleware;

use Closure;

class HttpsProtocol
{
    public function handle($request, Closure $next)
    {
        if (! $request->secure() && env('APP_ENV') === 'production')
            return redirect()->secure($request->getRequestUri());

        return $next($request);
    }
}