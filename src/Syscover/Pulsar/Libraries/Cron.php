<?php namespace Syscover\Pulsar\Libraries;

use Syscover\Pulsar\Models\AdvancedSearchTask;
use Maatwebsite\Excel\Facades\Excel;

class Cron
{

    public static function checkAdvancedSearch()
    {
        $advancedSearchs = AdvancedSearchTask::builder()->get();

        foreach ($advancedSearchs as $advancedSearch)
        {
            $parameters['start']    = $advancedSearch->start_022;
            $parameters['length']   = $advancedSearch->length_022;
            // TODO SET ORDER
            $parameters['order']['column']  = 'id';
            $parameters['order']['dir']     = 'asc';

            // get data from model
            $objects = call_user_func($advancedSearch->model_022 . '::getIndexRecords', null, $parameters);
            
            Excel::load(storage_path('exports') . '/' . $advancedSearch->filename_022 . '.' . $advancedSearch->extension_file_022, function($excel)  use ($objects) {
                
                // Set sheet
                $excel->sheet('Data', function ($sheet) use ($objects){
                    $sheet->fromArray($objects->toArray(), null, 'A' . ($sheet->getHighestRow() + 1), false, false);
                });

            }, null, true)->store($advancedSearch->extension_file_022);

            $advancedSearch->start_022 = $advancedSearch->start_022 + $advancedSearch->length_022;
            $advancedSearch->save();
        }
    }
}