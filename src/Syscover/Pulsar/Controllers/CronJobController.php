<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Models\Package;
use Syscover\Pulsar\Models\CronJob;
use Cron\CronExpression;
use Syscover\Pulsar\Traits\TraitController;

/**
 * Class CronJobController
 * @package Syscover\Pulsar\Controllers
 */

class CronJobController extends Controller
{
    use TraitController;

    protected $routeSuffix  = 'cronJob';
    protected $folder       = 'cron_job';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_011', 'name_011', 'name_012', 'key_011', 'cron_expression_011', ['data' => 'active_011', 'type' => 'active'], ['data' => 'last_run_011', 'type' => 'date'], ['data' => 'next_run_011', 'type' => 'date']];
    protected $nameM        = 'name_012';
    protected $model        = CronJob::class;
    protected $icon         = 'icomoon-icon-stopwatch';
    protected $objectTrans  = 'cronjob';

    public function jsonCustomDataBeforeActions($aObject, $actionUrlParameters, $parameters)
    {
        return session('userAcl')->allows($this->resource, 'access')? '<a class="btn btn-xs bs-tooltip" href="' . route('run' . ucfirst($this->routeSuffix), [$aObject['id_011'], $this->request->input('iDisplayStart')]) . '" data-original-title="' . trans('pulsar::pulsar.run') . '"><i class="fa fa-bolt"></i></a>' : null;
    }

    public function run($id, $offset = 0)
    {
        $cronJob  = CronJob::find($id);
        $callable = config('cron.' . $cronJob->key_011); // don't use helper config to dont't marked like error

        call_user_func($callable); // call to static method

        return redirect()->route($this->routeSuffix, $offset)->with([
            'msg'        => 1,
            'txtMsg'     => trans('pulsar::pulsar.action_successful', ['name' => $cronJob->name_011])
        ]);
    }
    
    public function createCustomRecord($parameters)
    {
        $parameters['packages'] = Package::all();
        
        return $parameters;
    }
    
    public function storeCustomRecord($parameters)
    {
        $cron = CronExpression::factory($this->request->input('cronExpression'));

        CronJob::create([
            'name_011'              => $this->request->input('name'),
            'package_011'           => $this->request->input('package'),
            'cron_expression_011'   => $this->request->input('cronExpression'),
            'key_011'               => $this->request->input('key'),
            'last_run_011'          => 0,
            'next_run_011'          => $cron->getNextRunDate()->getTimestamp(),
            'active_011'            => $this->request->input('active', 0)
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
            'name_011'              => $this->request->input('name'),
            'package_011'           => $this->request->input('package'),
            'cron_expression_011'   => $this->request->input('cronExpression'),
            'key_011'               => $this->request->input('key'),
            'active_011'            => $this->request->input('active', 0)
        ]);
    }
}