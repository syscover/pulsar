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
            $parameters = json_decode($advancedSearch->parameters_022, true);

            $parametersCount            = $parameters;
            $parametersCount['count']   = true;
            $nFilteredTotal             = call_user_func($advancedSearch->model_022 . '::getIndexRecords', null, $parametersCount);

            $lastInteraction = false;
            // if is the last interaction, set length to get just last record
            if(($parameters['start'] + $parameters['length']) >= $nFilteredTotal)
            {
                $parameters['length'] = $nFilteredTotal;
                $lastInteraction = true;
            }
            
            // get data from model
            $objects = call_user_func($advancedSearch->model_022 . '::getIndexRecords', null, $parameters);
            
            Excel::load(storage_path('exports') . '/' . $advancedSearch->filename_022 . '.' . $advancedSearch->extension_file_022, function($excel)  use ($objects) {
                
                // Set sheet
                $excel->sheet('Data', function ($sheet) use ($objects){
                    $sheet->fromArray($objects->toArray(), null, 'A' . ($sheet->getHighestRow() + 1), false, false);
                });

            }, null, true)->store($advancedSearch->extension_file_022);

            if($lastInteraction)
            {
                // send mail an delete record????   
            }
            else
            {
                $advancedSearch->start_022 = $advancedSearch->start_022 + $advancedSearch->length_022;
                $advancedSearch->save();
            }
        }
    }
}