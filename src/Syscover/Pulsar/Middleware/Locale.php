<?php namespace Syscover\Pulsar\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // set app locale
        config(['app.locale' => auth('pulsar')->user()->lang_id_010]);

        // change app locale
        App::setLocale(auth('pulsar')->user()->lang_id_010);

        return $next($request);
    }

}
