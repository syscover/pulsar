<?php namespace Pulsar\Pulsar\Controllers;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Support\Facades\Session,
    Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\URL,
    Illuminate\Support\Facades\Config,
    Illuminate\Support\Facades\Lang,

    Illuminate\Support\Facades\Redirect,
    Cron\CronExpression,

    Pulsar\Pulsar\Models\Package,
    Pulsar\Pulsar\Models\CronJob;
use Pulsar\Pulsar\Traits\ControllerTrait;

class CronJobs extends BaseController
{
    use ControllerTrait;

    protected $resource     = 'admin-cron';
    protected $routeSuffix  = 'CronJob';
    protected $folder       = 'cron_jobs';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_043', 'name_043', 'name_012', 'key_043', 'cron_expression_043', ['name' => 'active_043', 'type' => 'active'], ['name' => 'last_run_043', 'type' => 'date'], ['name' => 'next_run_043', 'type' => 'date']];
    protected $nameM        = 'name_012';
    protected $model        = '\Pulsar\Pulsar\Models\CronJob';

    public function jsonCustomDataBeforeActions($aObject)
    {
        return  Session::get('userAcl')->isAllowed(Auth::user()->profile_010, $this->resource, 'access')? '<a class="btn btn-xs bs-tooltip" href="' . route('run' . $this->routeSuffix, [$aObject['id_043'], Input::get('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.run') . '"><i class="icon-bolt"></i></a>' : null;
    }
    
    public function run($id, $offset=0)
    {
        $cronJob    = CronJob::find($id);
        $comand     = config('cron.' . $cronJob->key_043);

        $comand(); // run cron

        return Redirect::route($this->routeSuffix, $offset)->with([
            'msg'        => 1,
            'txtMsg'     => Lang::get('pulsar::pulsar.action_successful', ['name' => $cronJob->name_043])
        ]);
    }
    
    public function createCustomRecord()
    {
        $data['packages']        = Package::all();
        
        return $data;
    }
    
    public function storeCustomRecord()
    {
        $cron = CronExpression::factory(Input::get('cronExpresion'));
        CronJob::create([
            'name_043'              => Input::get('name'),
            'package_043'           => Input::get('package'),
            'cron_expression_043'   => Input::get('cronExpression'),
            'key_043'               => Input::get('key'),
            'last_run_043'          => 0,
            'next_run_043'          => $cron->getNextRunDate()->getTimestamp(),
            'active_043'            => Input::get('active', 0)
        ]);
    }
    
    public function editCustomRecord($data)
    {
        $data['packages']        = Package::all();
        $date = new \DateTime();
        $data['ultimaEjecucion'] = $date->setTimestamp($data['cronJob']->last_run_043)->format('d-m-Y H:i:s');
        $data['siguienteEjecucion'] = $date->setTimestamp($data['cronJob']->next_run_043)->format('d-m-Y H:i:s');

        return $data;
    }
    
    public function updateCustomRecord($id)
    {
        CronJob::where('id_043', $id)->update([
            'name_043'              => Input::get('name'),
            'package_043'           => Input::get('package'),
            'cron_expression_043'   => Input::get('cronExpression'),
            'key_043'               => Input::get('key'),
            'active_043'            => Input::get('active', 0)
        ]);
    }
}