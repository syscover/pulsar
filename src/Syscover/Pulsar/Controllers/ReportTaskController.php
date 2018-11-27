<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Syscover\Pulsar\Libraries\Cron;
use Syscover\Pulsar\Models\ReportTask;

/**
 * Class ReportTaskController
 * @package Syscover\Pulsar\Controllers
 */

class ReportTaskController extends Controller
{
    protected $routeSuffix  = 'reportTask';
    protected $folder       = 'report_task';
    protected $package      = 'pulsar';
    protected $indexColumns = ['id_023', 'email_023', 'subject_023'];
    protected $nameM        = 'subject_023';
    protected $model        = ReportTask::class;
    protected $icon         = 'fa fa-area-chart';
    protected $objectTrans  = 'report';

    public function jsonCustomDataBeforeActions($aObject, $actionUrlParameters, $parameters)
    {
        return is_allowed($this->resource, 'access')? '<a class="btn btn-xs bs-tooltip" href="' . route('run' . ucfirst($this->routeSuffix), [$aObject['id_023'], $this->request->input('start')]) . '" data-original-title="' . trans('pulsar::pulsar.run') . '"><i class="fa fa-bolt"></i></a>' : null;
    }

    public function run($id, $offset = 0)
    {
        $reportTask = ReportTask::builder()->find($id);

        // run task
        $response = Cron::executeReportTask($reportTask, 'download');


        if(! $response)
        {
            return redirect()->route($this->routeSuffix, $offset)->with([
                'msg'        => 2,
                'txtMsg'     => trans('pulsar::pulsar.message_error_has_not_results', ['name' => $reportTask->subject_023])
            ]);
        }
        else
        {
            return redirect()->route($this->routeSuffix, $offset)->with([
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.action_successful', ['name' => $reportTask->subject_023])
            ]);
        }
    }

    public function createCustomRecord($parameters)
    {
        $parameters['extensionsExportFile'] = config('pulsar.extensionsExportFile');
        $parameters['ccEmails']             = [];
        $parameters['frequencies']          = array_map(function($object) {
            $object->name = trans($object->name);
            return $object;
        }, config('pulsar.frequencies'));

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        // get data about frequency
        $frequency                  = Cron::getFrequencyData((int)$this->request->input('frequency'));

        ReportTask::create([
            'date_023'              => date('U'),
            'user_id_023'           => auth()->guard('pulsar')->user()->id_010,
            'email_023'             => $this->request->input('email'),
            'cc_023'                => $this->request->input('jsonCcEmails'),
            'subject_023'           => $this->request->input('subject'),
            'filename_023'          => $this->request->input('filename'),
            'extension_file_023'    => $this->request->input('extensionFile'),
            'frequency_023'         => $this->request->input('frequency'),
            'from_023'              => $this->request->input('from')? \DateTime::createFromFormat(config('pulsar.datePattern') . ' H:i', $this->request->input('from'))->getTimestamp() : null,
            'until_023'             => $this->request->input('until')? \DateTime::createFromFormat(config('pulsar.datePattern') . ' H:i', $this->request->input('until'))->getTimestamp() : null,
            'delivery_day_023'      => $this->request->has('delivery_day')? $this->request->input('delivery_day') : null,
            'last_run_023'          => null,
            'next_run_023'          => $frequency['nextRun'],
            'parameters_023'        => null,
            'sql_023'               => $this->request->input('sql')
        ]);
    }

    public function editCustomRecord($parameters)
    {
        $parameters['extensionsExportFile'] = config('pulsar.extensionsExportFile');
        $parameters['ccEmails']             = [];
        $parameters['frequencies']          = array_map(function($object) {
            $object->name = trans($object->name);
            return $object;
        }, config('pulsar.frequencies'));

        // transform json to array and get ccEmails object
        $ccEmails = json_decode($parameters['object']->cc_023);
        if(is_array($ccEmails) && count($ccEmails) > 0)
        {
            foreach($ccEmails as $ccEmail)
            {
                $parameters['ccEmails'][] = [
                    'value' => $ccEmail->label,
                    'label' => $ccEmail->label
                ];
            }
        }

        return $parameters;
    }

    public function updateCustomRecord($parameters)
    {
        // get data about frequency
        $frequency                  = Cron::getFrequencyData((int)$this->request->input('frequency'));

        ReportTask::where('id_023', $parameters['id'])->update([
            'email_023'             => $this->request->input('email'),
            'cc_023'                => $this->request->input('jsonCcEmails'),
            'subject_023'           => $this->request->input('subject'),
            'filename_023'          => $this->request->input('filename'),
            'extension_file_023'    => $this->request->input('extensionFile'),
            'frequency_023'         => $this->request->input('frequency'),
            'from_023'              => $this->request->input('from')? \DateTime::createFromFormat(config('pulsar.datePattern') . ' H:i', $this->request->input('from'))->getTimestamp() : null,
            'until_023'             => $this->request->input('until')? \DateTime::createFromFormat(config('pulsar.datePattern') . ' H:i', $this->request->input('until'))->getTimestamp() : null,
            'delivery_day_023'      => $this->request->has('delivery_day')? $this->request->input('delivery_day') : null,
            'next_run_023'          => $frequency['nextRun'],
            'parameters_023'        => null,
            'sql_023'               => $this->request->input('sql')
        ]);
    }
}