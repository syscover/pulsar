<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Support\Facades\DB;
use Syscover\Pulsar\Core\Controller;

// uses to testing
use MaatwebsiteOld\ExcelOld\Facades\Excel;
use Syscover\Pulsar\Models\ReportTask;


/**
 * Class TestingController
 * @package Syscover\Pulsar\Controllers
 */

class TestingController extends Controller
{
    public function testing()
    {

        $reportTasks = ReportTask::builder()
            ->get();

        foreach ($reportTasks as $reportTask)
        {
            // Execute query from report task
            $response = DB::select(DB::raw($reportTask->sql_023));

            // if has results from query
            if(count($response) === 0)
                dd('no hay resultados');

            // format response to manage with collections
            $response = collect(array_map(function($item) {
                return $item;
            }, $response));

            dd($response);
        }



    }
}
