<?php namespace Syscover\Pulsar\Middleware;

use Closure;

class Permission
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
		// check permission user, all parameters ['resource', 'action'] are passed in route.php file
		if(!session('userAcl')->allows($request->route()->getAction()['resource'], $request->route()->getAction()['action']))
            return view('pulsar::errors.default', [
                'error'     => 403,
                'message'   => trans('pulsar::pulsar.message_error_403')
            ]);

		return $next($request);
	}
}