<?php namespace Syscover\Pulsar\Libraries;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Syscover\Pulsar\Models\AdvancedSearchTask;
use Maatwebsite\Excel\Facades\Excel;

class Cron
{

    public static function checkAdvancedSearchExports()
    {
        // get advanced search without create yet
        $advancedSearches = AdvancedSearchTask::builder()
            ->where('created_022', false)
            ->get();

        foreach ($advancedSearches as $advancedSearch)
        {
            // get parametes from advnaced search
            $parameters                 = json_decode($advancedSearch->parameters_022, true);

            // config variables to count n records
            $parametersCount            = $parameters;
            $parametersCount['count']   = true;
            $nFilteredTotal             = call_user_func($advancedSearch->model_022 . '::getIndexRecords', null, $parametersCount);
            $lastInteraction            = false; // flag to know if this is the las call to do
            
            // if is the last interaction, set length to get just last record
            if(($parameters['start'] + $parameters['length']) >= $nFilteredTotal)
            {
                $parameters['length']   = $nFilteredTotal;
                $lastInteraction        = true;
            }
            
            // get data from model
            $objects = call_user_func($advancedSearch->model_022 . '::getIndexRecords', null, $parameters);

            // get de first object, to konow properties from model, table name, columns, etc.
            $object = $objects->first();

            // get filename to load
            $filename = storage_path('exports') . '/' . $advancedSearch->filename_022 . '.' . $advancedSearch->extension_file_022;

            // create spreadsheet to export data
            Excel::load($filename, function($excel)  use ($parameters, $objects, $object) {

                // get sheet
                $excel->sheet('Data', function ($sheet) use ($parameters, $objects, $object) {

                    // get all attributes from model and transform
                    // to array only the keys, to know columns names
                    $headers = collect($object->getAttributes())
                        ->keys()
                        ->toArray();

                    // if has operations columns, read las row (operations row), and delete
                    if(isset($parameters['operationColumns']))
                    {
                        $highestRow         = $sheet->getHighestRow();          // get highest row
                        $highestColumn      = $sheet->getHighestColumn();       // get highest column

                        // get last row
                        $oldOperationsRow   = $sheet->rangeToArray('A' . $highestRow . ':' . $highestColumn . $highestRow, null, true, false)[0];

                        // transform array to collection to manage elements
                        $operationColumns   = collect($parameters['operationColumns']);

                        // create empty operations row to be filled after
                        $operationsRow      = array_fill(0, count($headers) -1, '');

                        // set operations columns row
                        // key from headers correspond with the key from oldOperationColumns
                        foreach ($headers as $key => &$columnName)
                        {
                            // first, check if column has any operation on itself
                            $operationColumn = $operationColumns->where('column', $columnName);

                            if($operationColumn->count() > 0)
                            {
                                // set operation
                                $operationColumn = $operationColumn->first();
                                switch ($operationColumn['operation'])
                                {
                                    case 'sum':
                                        $operationsRow[$key] = $objects->sum($columnName)
                                            + $oldOperationsRow[$key]; // sum old operations row
                                        break;
                                }
                            }
                        }

                        // remove operations columns row
                        $sheet->removeRow($highestRow);

                    }

                    // set operations row and styles
                    if(isset($parameters['operationColumns']))
                    {
                        // set new data in spreadsheet, but overwrite old operations row
                        $sheet->fromArray($objects->toArray(), null, 'A' . ($sheet->getHighestRow()), false, false);

                        $sheet->appendRow($operationsRow);
                        $sheet->cells('A' . $sheet->row(0)->getHighestRow() . ':' . $sheet->row(0)->getHighestDataColumn() . $sheet->row(0)->getHighestRow(), function ($cells) {
                            $cells->setBackground('#F8F8F8');
                            $cells->setFontWeight('bold');
                        });
                    }
                    else
                    {
                        // set new data in spreadsheet
                        $sheet->fromArray($objects->toArray(), null, 'A' . ($sheet->getHighestRow() + 1), false, false);
                    }
                });

            }, null, true)->store($advancedSearch->extension_file_022);

            if($lastInteraction)
            {
                // get user to send email
                $user = $advancedSearch->getUser;
                
                // send email to user
                $dataMessage = [
                    'emailTo'           => $user->email_010,
                    'nameTo'            => $user->name_010 . ' ' . $user->surname_010,
                    'subject'           => trans('pulsar::pulsar.message_advanced_search_exports'),
                    'token'             => Crypt::encrypt($advancedSearch->id_022),
                    'advancedSearch'    => $advancedSearch
                ];
                
                Mail::send('pulsar::emails.advanced_search_exports_notification', $dataMessage, function($m) use ($dataMessage) {
                    $m->to($dataMessage['emailTo'], $dataMessage['nameTo'])
                        ->subject($dataMessage['subject']);
                });

                // send mail
                $advancedSearch->created_022 = true;

                // todo, delete advanced search from database?
            }
            else
            {
                // set next paginate parameters
                $parameters['start']            = $parameters['start'] + $parameters['length'];
                $advancedSearch->parameters_022 = json_encode($parameters);
            }

            // save model with new data interaction
            $advancedSearch->save();
        }
    }
}