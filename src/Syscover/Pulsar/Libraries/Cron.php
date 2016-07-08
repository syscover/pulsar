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
        $advancedSearchs = AdvancedSearchTask::builder()
            ->where('created_022', false)
            ->get();

        foreach ($advancedSearchs as $advancedSearch)
        {
            $parameters = json_decode($advancedSearch->parameters_022, true);

            $parametersCount            = $parameters;
            $parametersCount['count']   = true;
            $nFilteredTotal             = call_user_func($advancedSearch->model_022 . '::getIndexRecords', null, $parametersCount);
            $lastInteraction            = false;
            
            // if is the last interaction, set length to get just last record
            if(($parameters['start'] + $parameters['length']) >= $nFilteredTotal)
            {
                $parameters['length']   = $nFilteredTotal;
                $lastInteraction        = true;
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
                // get user to send email
                $user = $advancedSearch->getUser;
                
                // send email to user
                $dataMessage = [
                    'emailTo'           => $user->email_010,
                    'nameTo'            => $user->name_010 . ' ' . $user->surname_010,
                    'subject'           => trans('pulsar::pulsar.message_advanced_search_exports_notification'),
                    'token'             => Crypt::encrypt($advancedSearch->id_022),
                    'advancedSearch'    => $advancedSearch
                ];
                
                Mail::send('pulsar::emails.advanced_search_exports_notification', $dataMessage, function($m) use ($dataMessage) {
                    $m->to($dataMessage['emailTo'], $dataMessage['nameTo'])
                        ->subject($dataMessage['subject']);
                });

                // send mail an delete record????
                $advancedSearch->created_022 = true;
            }
            else
            {
                $advancedSearch->start_022 = $advancedSearch->start_022 + $advancedSearch->length_022;
            }

            $advancedSearch->save();
        }
    }
}