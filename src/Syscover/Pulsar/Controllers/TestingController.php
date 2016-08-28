<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;

// uses to testing
use Maatwebsite\Excel\Facades\Excel;


/**
 * Class TestingController
 * @package Syscover\Pulsar\Controllers
 */

class TestingController extends Controller
{
    public function testing()
    {
        $this->request;

        //dd('ok');
        $objects = [];

        Excel::load(storage_path('exports') . '/test-vouchers.xls', function($excel)  use ($objects) {

            //dd($excel->limit(false,1));

            $sheet          = $excel->sheet('Data');            // get sheet by name
            $highestRow     = $sheet->getHighestRow();          // get highest row
            $highestColumn  = $sheet->getHighestColumn();       // get highest column

            $row            = $sheet->rangeToArray('A' . $highestRow . ':' . $highestColumn . $highestRow, null, true, false);

            dd($row);

            //$sheet->removeRow($highestRow);


            //dd($row);
            // get las row
//            $excel->sheet('Data', function ($sheet) {
//
//
//                dd($sheet->row($sheet->getHighestRow())->getView());
//
//            });

            // if has operations column, read las row (operations row), and delete
//                if($this->request->has('operationColumns'))
//                {
//                    $excel->limit($skip, $take);
//                }

            // Set rows in spreadsheet
//            $excel->sheet('Data', function ($sheet) use ($objects) {
//                $sheet->fromArray($objects->toArray(), null, 'A' . ($sheet->getHighestRow() + 1), false, false);
//            });

        }, null, true);
        //->store('xls')

    }
}