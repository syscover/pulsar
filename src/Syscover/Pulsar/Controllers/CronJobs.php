<?php namespace Syscover\Pulsar\Controllers;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Syscover\Pulsar\Models\Package;
use Syscover\Pulsar\Models\CronJob;
use Cron\CronExpression;
use Syscover\Pulsar\Traits\ControllerTrait;

class CronJobs extends Controller
{
    use ControllerTrait;

    protected $routeSuffix  = 'CronJob';
    protected $folder       = 'cron_jobs';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_011', 'name_011', 'name_012', 'key_011', 'cron_expression_011', ['data' => 'active_011', 'type' => 'active'], ['data' => 'last_run_011', 'type' => 'date'], ['data' => 'next_run_011', 'type' => 'date']];
    protected $nameM        = 'name_012';
    protected $model        = '\Syscover\Pulsar\Models\CronJob';
    protected $icon         = 'icomoon-icon-stopwatch';
    protected $objectTrans  = 'cronjob';

    public function jsonCustomDataBeforeActions($aObject)
    {
        return  Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'access')? '<a class="btn btn-xs bs-tooltip" href="' . route('run' . $this->routeSuffix, [$aObject['id_011'], Input::get('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.run') . '"><i class="icon-bolt"></i></a>' : null;
    }

    public function run($id, $offset = 0)
    {
        $cronJob    = CronJob::find($id);
        $comand     = Config::get('cron.' . $cronJob->key_011); // don't use helper config to dont't marked like error

        $comand(); // run task cron

        return Redirect::route($this->routeSuffix, $offset)->with([
            'msg'        => 1,
            'txtMsg'     => Lang::get('pulsar::pulsar.action_successful', ['name' => $cronJob->name_011])
        ]);
    }
    
    public function createCustomRecord($parameters)
    {
        $parameters['packages'] = Package::all();
        
        return $parameters;
    }
    
    public function storeCustomRecord($parameters)
    {
        $cron = CronExpression::factory(Input::get('cronExpression'));

        CronJob::create([
            'name_011'              => Input::get('name'),
            'package_011'           => Input::get('package'),
            'cron_expression_011'   => Input::get('cronExpression'),
            'key_011'               => Input::get('key'),
            'last_run_011'          => 0,
            'next_run_011'          => $cron->getNextRunDate()->getTimestamp(),
            'active_011'            => Input::get('active', 0)
        ]);
    }
    
    public function editCustomRecord($parameters)
    {
        $parameters['packages'] = Package::all();
        $date = new \DateTime();
        $parameters['lastRun'] = $date->setTimestamp($parameters['object']->last_run_011)->format('d-m-Y H:i:s');
        $parameters['nextRun'] = $date->setTimestamp($parameters['object']->next_run_011)->format('d-m-Y H:i:s');

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        CronJob::where('id_011', $parameters['id'])->update([
            'name_011'              => Input::get('name'),
            'package_011'           => Input::get('package'),
            'cron_expression_011'   => Input::get('cronExpression'),
            'key_011'               => Input::get('key'),
            'active_011'            => Input::get('active', 0)
        ]);
    }
}