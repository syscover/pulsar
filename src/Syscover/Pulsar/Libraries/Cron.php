<?php namespace Syscover\Pulsar\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Syscover\Pulsar\Models\AdvancedSearchTask;
use MaatwebsiteOld\ExcelOld\Facades\Excel;
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

            // *************************************************************
            // transform string data to number data, to operate with excel
            // *************************************************************
            $objects->transform(function ($item, $key) {
                $attributes = $item->getAttributes();
                foreach ($attributes as $key => $value)
                {
                    if(is_numeric($value) && strpos($value, '.') === false)
                        $item->{$key} = (int) $value;
                    elseif(is_numeric($value) && strpos($value, '.') !== false)
                        $item->{$key} = (float) $value;
                }
                return $item;
            });

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
            ->whereNotNull('next_run_023')
            ->where('next_run_023', '<', date('U'))
            ->get();

        foreach ($reportTasks as $reportTask)
            self::executeReportTask($reportTask);

    }

    /**
     * Function to return data from query, return false if has not any result
     *
     * @param   ReportTask  $reportTask
     * @param   string      $action
     * @return  boolean
     */
    public static function executeReportTask(ReportTask $reportTask, $action = 'store')
    {
        $parameters = [];
        if($reportTask->frequency_023 == 1) // one time
        {
            $parameters['from']    = $reportTask->from_023;
            $parameters['until']   = $reportTask->until_023;
        }

        // get data about frequency
        $frequency                  = self::getFrequencyData($reportTask->frequency_023, $parameters);

        // replace wildcards
        if(isset($frequency['from']))
            $reportTask->sql_023    = str_replace("#FROM#", $frequency['from'], $reportTask->sql_023);
        if(isset($frequency['until']))
            $reportTask->sql_023    = str_replace("#UNTIL#", $frequency['until'], $reportTask->sql_023);

        // Execute query from report task
        $objects = DB::connection('mysql2')->select(DB::raw($reportTask->sql_023));

        // if has results from query
        if(count($objects) == 0)
            return false;

        // *************************************************************
        // transform string data to number data, to operate with excel
        // *************************************************************
        foreach ($objects as &$object)
        {
            $fields = get_object_vars($object);
            foreach ($fields as $key => $value)
            {
                if(is_numeric($value) && strpos($value, '.') === false)
                {
                    $object->{$key} = (int) $value;
                }
                elseif(is_numeric($value) && strpos($value, '.') !== false)
                {
                    $object->{$key} = (float) $value;
                }
            }
        }

        // format response to manage with collections
        $objects = collect(array_map(function($item) {
            return collect($item);
        }, $objects));

        // ***************************
        // get sum columns from numerics fields
        // ***************************
        $object         = $objects->first();
        $operationsRow  = [];
        $object->map(function ($item, $key) use (&$operationsRow, $objects) {
            if(is_numeric($item))
            {
                try
                {
                    $operationsRow[$key] = $objects->sum($key);
                }
                catch (\Exception $e)
                {
                    Log::error($e->getMessage());
                }
            }
            else
            {
                $operationsRow[$key] = null;
            }
        });

        $filename = $reportTask->filename_023 . '-' . uniqid();

        // create spreadsheet to export data
        $excel = Excel::create($filename, function($excel) use ($objects, $operationsRow) {

            // set the title
            $excel->setTitle('Report')
                ->setCreator('Pulsar')
                ->setCompany('SYSCOVER');

            // set sheet
            $excel->sheet('Data', function ($sheet) use ($objects, $operationsRow) {

                // get keys from first element
                $headers = $objects->first()->keys()->toArray();

                // set data and headers
                $sheet->prependRow($headers);
                $sheet->fromArray($objects->toArray(), null, 'A2', true, false);
                $sheet->cells('A1:' . $sheet->row(0)->getHighestDataColumn() . '1', function ($cells) {
                    $cells->setBackground('#CCCCCC');
                    $cells->setFontWeight('bold');
                });

                // append row with sum numeric columns
                if(count($operationsRow) > 0)
                {
                    $sheet->appendRow($operationsRow);
                    $sheet->cells('A' . $sheet->row(0)->getHighestRow() . ':' . $sheet->row(0)->getHighestDataColumn() . $sheet->row(0)->getHighestRow(), function ($cells) {
                        $cells->setBackground('#F8F8F8');
                        $cells->setFontWeight('bold');
                    });
                }
            });
        });

        // get excel option
        if($action == 'store')
        {
            $excel->store($reportTask->extension_file_023);

            // transform json to array and get ccEmails object
            $ccEmailsJson   = json_decode($reportTask->cc_023);
            $ccEmails       = [];
            if(is_array($ccEmailsJson) && count($ccEmailsJson) > 0)
            {
                foreach($ccEmailsJson as $ccEmail)
                {
                    $ccEmails[] = $ccEmail->label;
                }
            }

            // delivery reports
            $dataMessage = [
                'emailTo'           => $reportTask->email_023,
                'cc'                => $ccEmails,
                'subject'           => $reportTask->subject_023,
                'token'             => Crypt::encrypt($reportTask->id_023),
                'filename'          => Crypt::encrypt($filename),
                'reportTask'        => $reportTask
            ];

            Mail::send('pulsar::emails.report_task_notification', $dataMessage, function($m) use ($dataMessage) {
                $m->to($dataMessage['emailTo'])
                    ->subject($dataMessage['subject']);

                // send copy to alternative emails
                foreach ($dataMessage['cc'] as $cc)
                    $m->cc($cc);
            });
        }
        else
        {
            $excel->download($reportTask->extension_file_023);
        }

        // updates time stamp of executions
        ReportTask::where('id_023', $reportTask->id_023)->update([
            'last_run_023' => $frequency['lastRun'],
            'next_run_023' => $frequency['nextRun']
        ]);

        return true;
    }

    /**
     * @param   integer   $frequency
     * @param   $parameters
     * @return  array
     */
    public static function getFrequencyData($frequency, $parameters = [])
    {
        $response['lastRun'] = date('U');
        $response['nextRun'] = null;

        // set time stamp according frequency
        switch ($frequency)
        {
            case 1: // one time, set dates values from fields
                $response['from']       = isset($parameters['from'])? $parameters['from'] : null;
                $response['until']      = isset($parameters['until'])? $parameters['until'] : null;
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
