<?php namespace Syscover\Pulsar\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Syscover\Pulsar\Models\AdvancedSearchTask;
use Maatwebsite\Excel\Facades\Excel;
use Syscover\Pulsar\Models\ReportTask;

class Cron
{

    /**
     * Function to manage queue from advanced search
     */
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

    /**
     * Function to manage reports task to delivery
     *
     * @return null
     */
    public static function checkReportTaskDelivery()
    {
        // get report tasks
        $reportTasks = ReportTask::builder()
            ->get();

        foreach ($reportTasks as $reportTask)
        {
            // check if report task has to be executed
            if($reportTask->next_run_023 != null && $reportTask->next_run_023 < date('U'))
                self::executeReportTask($reportTask);
        }
    }

    /**
     * @param   ReportTask  $reportTask
     * @param   string      $action
     * @param   array       $parameters
     * @return  null
     */
    public static function executeReportTask(ReportTask $reportTask, $action = 'store', $parameters = [])
    {
        // get data about frequency
        $frequency                  = self::getFrequencyData($reportTask->frequency_023);

        // replace wildcards
        if(isset($from))
            $reportTask->sql_023   = str_replace("#FROM#", $reportTask->sql_023, $frequency['from']);
        if(isset($until))
            $reportTask->sql_023   = str_replace("#UNTIL#", $reportTask->sql_023, $frequency['until']);


        // Execute query from report task
        $response = DB::select(DB::raw($reportTask->sql_023));

        // if has results from query
        if(count($response) === 0)
            return null;

        // format response to manage with collections
        $response = collect(array_map(function($item) {
            return collect($item);
        }, $response));

        // create spreadsheet to export data
        $excel = Excel::create($reportTask->filename_023 . '-' . uniqid(), function($excel) use ($response) {

            // set the title
            $excel->setTitle('Report')
                ->setCreator('Pulsar')
                ->setCompany('SYSCOVER');

            // set sheet
            $excel->sheet('Data', function ($sheet) use ($response) {

                // get keys from first element
                $headers = $response->first()->keys()->toArray();

                // set data and headers
                $sheet->prependRow($headers);
                $sheet->fromArray($response->toArray(), null, 'A2', false, false);
                $sheet->cells('A1:' . $sheet->row(0)->getHighestDataColumn() . '1', function ($cells) {
                    $cells->setBackground('#CCCCCC');
                    $cells->setFontWeight('bold');
                });
            });
        });

        // get excel option
        if($action == 'store')
        {
            $excel->store($reportTask->extension_file_023);

//            // send email to user
//            $dataMessage = [
//                'emailTo'           => $user->email_010,
//                'nameTo'            => $user->name_010 . ' ' . $user->surname_010,
//                'subject'           => trans('pulsar::pulsar.message_advanced_search_exports'),
//                'token'             => Crypt::encrypt($advancedSearch->id_022),
//                'advancedSearch'    => $advancedSearch
//            ];
//
//            Mail::send('pulsar::emails.advanced_search_exports_notification', $dataMessage, function($m) use ($dataMessage) {
//                $m->to($dataMessage['emailTo'], $dataMessage['nameTo'])
//                    ->subject($dataMessage['subject']);
//            });
        }
        else
        {
            $excel->download($reportTask->extension_file_023);
        }

        // updates time stamp of executions
        $reportTask->last_run_023 = $frequency['lastRun'];
        $reportTask->next_run_023 = $frequency['nextRun'];
        $reportTask->save();
    }

    /**
     * @param   integer   $frequency
     * @param   string    $from
     * @param   string    $until
     * @return  array
     */
    public static function getFrequencyData($frequency, $from = null, $until = null)
    {
        $response['lastRun'] = date('U');
        $response['nextRun'] = null;

        // set time stamp according frequency
        switch ($frequency)
        {
            case 1: // one time, set dates values from fields
                $response['from']       = isset($parameters['from'])? $parameters['from'] : null;
                $response['until']      = isset($parameters['until'])? $parameters['from'] : null;
                break;

            case 2: // daily, get values of last day
                $response['from']       = strtotime('last day 00:00:00');
                $response['until']      = strtotime('last day 23:59:59');
                $response['nextRun']    = strtotime('tomorrow');
                break;

            case 3: // weekly, get values of last week
                $response['from']       = strtotime('monday last week 00:00:00');
                $response['until']      = strtotime('sunday last week 23:59:59');
                $response['nextRun']    = strtotime('monday next week 00:00:00');
                break;

            case 4: // monthly, get values of last month
                $response['from']       = strtotime('first day of last month 00:00:00');
                $response['until']      = strtotime('last day of last month 23:59:59');
                $response['nextRun']    = strtotime('first day of next month 00:00:00');
                break;

            case 5: // quarterly, get values of last quarter
                $quarter                = Miscellaneous::getQuarter('last');
                $response['from']       = $quarter['starDate'];
                $response['until']      = $quarter['endDate'];

                $quarter                = Miscellaneous::getQuarter('current');
                $response['nextRun']    = $quarter['endDate'];
                break;

            default:
                $response['from']   = null;
                $response['until']  = null;
        }

        return $response;
    }
}