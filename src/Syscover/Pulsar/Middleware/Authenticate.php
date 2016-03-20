<?php namespace Syscover\Pulsar\Middleware;

use Closure;

class Authenticate
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = 'pulsar')
	{
		if(auth($guard)->guest())
		{
			if($request->ajax())
				return response('Unauthorized.', 401);
			else
				return redirect()->guest(route('getLogin'));
		}

		return $next($request);
	}
}