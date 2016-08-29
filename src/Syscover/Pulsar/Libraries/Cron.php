<?php namespace Syscover\Pulsar\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Syscover\Pulsar\Models\AdvancedSearchTask;
use Maatwebsite\Excel\Facades\Excel;
use Syscover\Pulsar\Models\ReportTask;
use Syscover\Pulsar\Models\User;

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

    public static function checkReportTaskDelivery()
    {

        $from   = strtotime('first day of last month 00:00:00');
        $to     = strtotime('last day of last month 23:59:59');

        dd(date('d-m-Y H:i:s', $to));

        $month  = (int)date("n");
        $year   = (int)date("Y");
        $day    = (int)date("j");



        //mktime(0,0,0, );


        $reportTasks = ReportTask::builder()
            ->get();

        foreach ($reportTasks as $reportTask)
        {
            $from   = null;
            $to     = null;

            // daily, get values of last day
            if($reportTask->frequency_023 == 2)
            {
                $from   = strtotime('last day 00:00:00');
                $to     = strtotime('last day 23:59:59');
            }

            // weekly, get values of last week
            if($reportTask->frequency_023 == 3)
            {
                $from   = strtotime('monday last week 00:00:00');
                $to     = strtotime('sunday last week 23:59:59');
            }

            // monthly, get values of last month
            if($reportTask->frequency_023 == 4)
            {
                $from   = strtotime('first day of last month 00:00:00');
                $to     = strtotime('last day of last month 23:59:59');
            }

            // quarterly, get values of last quarter
            if($reportTask->frequency_023 == 4)
            {


//            case 'this_quarter':
//
//                  $current_month = date('m');
//                  $current_year = date('Y');
//                  if($current_month>=1 && $current_month<=3)
//                  {
//                      $start_date = strtotime('1-January-'.$current_year);  // timestamp or 1-Januray 12:00:00 AM
//                      $end_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM means end of 31 March
//                  }
//                  else  if($current_month>=4 && $current_month<=6)
//                  {
//                      $start_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM
//                      $end_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM means end of 30 June
//                  }
//                  else  if($current_month>=7 && $current_month<=9)
//                  {
//                      $start_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM
//                      $end_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM means end of 30 September
//                  }
//                  else  if($current_month>=10 && $current_month<=12)
//                  {
//                      $start_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM
//                      $end_date = strtotime('1-January-'.($current_year+1));  // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
//                  }
//                break;
//
//                case 'last_quarter':
//
//                    $current_month = date('m');
//                    $current_year = date('Y');
//
//                    if($current_month>=1 && $current_month<=3)
//                    {
//                      $start_date = strtotime('1-October-'.($current_year-1));  // timestamp or 1-October Last Year 12:00:00 AM
//                      $end_date = strtotime('1-January-'.$current_year);  // // timestamp or 1-January  12:00:00 AM means end of 31 December Last year
//                    }
//                    else if($current_month>=4 && $current_month<=6)
//                    {
//                      $start_date = strtotime('1-January-'.$current_year);  // timestamp or 1-Januray 12:00:00 AM
//                      $end_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM means end of 31 March
//                    }
//                    else  if($current_month>=7 && $current_month<=9)
//                    {
//                      $start_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM
//                      $end_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM means end of 30 June
//                    }
//                    else  if($current_month>=10 && $current_month<=12)
//                    {
//                      $start_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM
//                      $end_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM means end of 30 September
//                    }
//                break;
            }


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
            $excel = Excel::create(uniqid() . '_' . $reportTask->filename_023, function($excel) use ($response) {

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
            })->download($reportTask->extension_file_023);
                //->store($reportTask->extension_file_023);
        }
    }
}