<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Syscover\Pulsar\Models\AdvancedSearchTask;
use Syscover\Pulsar\Models\ReportTask;

/**
 * Class DownloadController
 * @package Syscover\Pulsar\Controllers
 */

class DownloadController extends BaseController
{
    public function downloadAdvancedSearch(Request $request)
    {
        // get parameters from url route
        $parameters     = $request->route()->parameters();

        $advancedSearch = AdvancedSearchTask::builder()->find(Crypt::decrypt($parameters['token']));
        
        if($advancedSearch === null)
            abort(404);

        return response()->download(storage_path('exports') . '/' . $advancedSearch->filename_022 . '.' . $advancedSearch->extension_file_022);
    }

    public function downloadAReportTask(Request $request)
    {
        // get parameters from url route
        $parameters = $request->route()->parameters();

        $reportTask = ReportTask::builder()->find(Crypt::decrypt($parameters['token']));
        $filename   = Crypt::decrypt($parameters['filename']);

        if($reportTask === null)
            abort(404);

        return response()->download(storage_path('exports') . '/' . $filename . '.' . $reportTask->extension_file_023);
    }
}