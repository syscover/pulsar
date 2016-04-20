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
		$action = $request->route()->getAction();

		if(isset($action['resource']))
			if(!is_allowed($action['resource'], $action['action']))
				return view('pulsar::errors.default', [
					'error'     => 403,
					'message'   => trans('pulsar::pulsar.message_error_403')
				]);

		return $next($request);
	}
}