<?php namespace Syscover\Pulsar\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;

class Permission {

	protected $auth;
	protected $pulsarAcl;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->pulsarAcl 	= Session::get('userAcl');
		$this->auth 		= $auth;
	}

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
		if(!$this->pulsarAcl->isAllowed($this->auth->user()->profile_010, $request->route()->getAction()['resource'], $request->route()->getAction()['action']))
		{
			abort(403, 'Permission denied.');
			//abort(403,);
			//return response('Unauthorized.', 404);
		}

		return $next($request);
	}

}
