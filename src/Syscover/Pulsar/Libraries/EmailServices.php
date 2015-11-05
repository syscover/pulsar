<?php namespace Syscover\Pulsar\Libraries;

/**
 * @package		Pulsar
 * @author		Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL.
 * @license
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource  Librarie that instance helper functions
 */

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\MessageBag;

class EmailServices {

    /**
     *  Function that send a email
     *
     * @access	public
     * @param   array   $data
     * @return  boolean
     */
    public static function sendEmail($data)
    {
        $data = self::setTemplate($data);

        if(isset($data['html']) && isset($data['text']))
        {
            Mail::send(['pulsar::common.views.html_display', 'pulsar::common.views.text_display'], $data, function ($message) use ($data) {
                $message->to($data['email'], $data['name'])->subject($data['subject']);
                if(isset($data['replyTo'])) $message->replyTo($data['replyTo']);
            });
            return true;
        }
        elseif(isset($data['html']))
        {
            Mail::send(['html' =>'pulsar::common.views.html_display'], $data, function ($message) use ($data) {
                $message->to($data['email'], $data['name'])->subject($data['subject']);
                if(isset($data['replyTo'])) $message->replyTo($data['replyTo']);
            });
            return true;
        }
        elseif(isset($data['text']))
        {
            Mail::send(['text' =>'pulsar::common.views.text_display'], $data, function ($message) use ($data) {
                $message->to($data['email'], $data['name'])->subject($data['subject']);
                if(isset($data['replyTo'])) $message->replyTo($data['replyTo']);
            });
            return true;
        }

        return false;
    }

    /**
     *  Function that checks the output of a server mail account
     *
     * @access	public
     * @param   array   $account
     * @return  boolean || string
     */
    public static function testEmailAccount($account)
    {
        $data['name']   = $account['name_013'];
        $data['email']  = $account['email_013'];
        $data['subject'] = "Send test - Envío de pruebas";
        $data['text']   = "

Congratulations,
this is a test email, if you are receiving this email, your account has been configured correctly.

Enhorabuena,
este es un envío de pruebas, si está recibiendo este correo, su cuenta se ha configurado correctamente.";

        //set outgoingserver
        config(['mail.host'          => $account['outgoing_server_013']]);
        config(['mail.port'          => $account['outgoing_port_013']]);
        config(['mail.from'          => ['address' => $account['email_013'], 'name' => $account['name_013']]]);
        config(['mail.encryption'    => $account['outgoing_secure_013'] == ''? null : $account['outgoing_secure_013']]);
        config(['mail.username'      => $account['outgoing_user_013']]);
        config(['mail.password'      => Crypt::decrypt($account['outgoing_pass_013'])]);

        try
        {
            self::sendEmail($data);
        }
        catch (\Swift_TransportException $swiftTransportException)
        {
            $messageBag = new MessageBag;
            $messageBag->add('error', $swiftTransportException->getMessage());

            return $messageBag;
        }
        return true;
    }

    /**
     *  Patterns function to replace their values
     *
     * @access	public
     * @param   array   $data
     * @return  array
     */
    public static function setTemplate($data)
    {
        /***********************************
         * Message subject replace
         ************************************/

        //if (isset($data['message']))    $data['subject']    = str_replace("#message#", $data['message'], $data['subject']);      // Message coded ID, to track (comunik)
        if (isset($data['contactKey'])) $data['subject']    = str_replace("#contactKey#",   $data['contactKey'],                    $data['subject']);  // Contact coded ID, to track (comunik)
        if (isset($data['company']))    $data['subject']    = str_replace("#company#",      $data['company'],                       $data['subject']);  // Company name (comunik)
        if (isset($data['name']))       $data['subject']    = str_replace("#name#",         $data['name'],                          $data['subject']);  // Contact name (comunik)
        if (isset($data['surname']))    $data['subject']    = str_replace("#surname#",      $data['surname'],                       $data['subject']);  // Contact surname (comunik)
        if (isset($data['birthDate']))  $data['subject']    = str_replace("#birthDate#",    $data['birthDate'],                     $data['subject']);  // BirthDate (comunik)
        if (isset($data['email']))      $data['subject']    = str_replace("#email#",        $data['email'],                         $data['subject']);  // Contact email (comunik)
        $data['subject']                                    = str_replace("#date#",         date(config('pulsar.datePattern')),     $data['subject']);  // Current date

        /***********************************
         * Message html format replace
         ************************************/
        if(isset($data['html']))
            $data = self::replaceWildcard($data, 'html');

        /***********************************
         * Message text format replace
         ************************************/
        if(isset($data['text']))
            $data = self::replaceWildcard($data, 'text');

        return $data;
    }

    /**
     *  Patterns function to replace their values
     *
     * @access	public
     * @param   array   $data
     * @param   string  $index
     * @return  array
     */
    private static function replaceWildcard($data, $index)
    {
        // Message body
        $data[$index] = str_replace("#link#", route('showComunikEmailCampaign', ['campaign' => '#campaign#', 'contactKey' => '#contactKey#']), $data[$index]);
        $data[$index] = str_replace("#unsubscribe#", route('getUnsubscribeComunikContact', ['contactKey' => '#contact#']) , $data[$index]);
        $data[$index] = str_replace("#pixel#", url(config('pulsar.appName')) . config('comunik.trackPixel'), $data[$index]);

        //if (isset($data['message']))    $data[$index]   = str_replace("#message#",    $data['message'],   $data[$index]);     // Message coded ID, to track (comunik)
        if (isset($data['contactKey']))     $data[$index]   = str_replace("#contactKey#",   $data['contactKey'],                   $data[$index]);     // Contact coded ID, to track (comunik)
        if (isset($data['company']))        $data[$index]   = str_replace("#company#",      $data['company'],                   $data[$index]);     // Company name (comunik)
        if (isset($data['name']))           $data[$index]   = str_replace("#name#",         $data['name'],                      $data[$index]);     // Contact name (comunik)
        if (isset($data['surname']))        $data[$index]   = str_replace("#surname#",      $data['surname'],                   $data[$index]);     // Contact surname (comunik)
        if (isset($data['birthDate']))      $data[$index]   = str_replace("#birthDate#",    $data['birthDate'],                 $data[$index]);     // birth date (comunik)
        if (isset($data['email']))          $data[$index]   = str_replace("#email#",        $data['email'],                     $data[$index]);     // Contact email (comunik)
        if (isset($data['campaign']))       $data[$index]   = str_replace("#campaign#",     $data['campaign'],                  $data[$index]);     // Campaign coded ID, to track (comunik)
        if (isset($data['historicalId']))   $data[$index]   = str_replace("#historicalId#", $data['historicalId'],              $data[$index]);     // Sending coded ID, to track (comunik)
        $data[$index]                                       = str_replace("#date#",         date(config('pulsar.datePattern')), $data[$index]);     // Current date

        // function designed to replace the word #subject# in the title of HTML templates
        if (isset($data['subject'])) $data[$index] = str_replace("#subject#", $data['subject'], $data[$index]);

        return $data;
    }
}