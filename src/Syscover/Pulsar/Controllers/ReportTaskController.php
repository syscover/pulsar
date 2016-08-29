<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
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


    public function createCustomRecord($parameters)
    {
        $parameters['extensionsExportFile'] = config('pulsar.extensionsExportFile');
        $parameters['frequencies']          = config('pulsar.frequencies');

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        ReportTask::create([
            'date_023'              => date('U'),
            'user_id_023'           => auth()->user()->id_010,
            'email_023'             => $this->request->input('email'),
            'subject_023'           => $this->request->input('subject'),
            'filename_023'          => $this->request->input('filename'),
            'extension_file_023'    => $this->request->input('extensionFile'),
            'frequency_023'         => $this->request->input('frequency'),
            'delivery_day_023'      => $this->request->input('delivery_day'),
            'last_run_023'          => 0,
            'next_run_023'          => 0,
            'parameters_023'        => null,
            'sql_023'               => $this->request->input('sql'),

        ]);
    }

    public function updateCustomRecord($parameters)
    {
        ReportTask::where('id_023', $parameters['id'])->update([
            'email_023'             => $this->request->input('email'),
        ]);
    }
}