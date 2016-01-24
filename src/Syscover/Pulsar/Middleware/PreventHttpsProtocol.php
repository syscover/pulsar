<?php namespace Syscover\Pulsar\Middleware;

use Closure;

class PreventHttpsProtocol {

    public function handle($request, Closure $next)
    {
        if ($request->secure() && env('APP_ENV') === 'prod')
        {
            return redirect($request->getRequestUri(), 302, [], false);
        }

        return $next($request);
    }
}