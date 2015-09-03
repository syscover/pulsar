<?php namespace Syscover\Pulsar\Controllers;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request;
use Syscover\Pulsar\Libraries\EmailServices;
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\EmailAccount;

class EmailAccountController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'EmailAccount';
    protected $folder       = 'email_account';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_013', 'name_013', ['data' => 'email_013', 'type' => 'email']];
    protected $nameM        = 'name_013';
    protected $model        = '\Syscover\Pulsar\Models\EmailAccount';
    protected $icon         = 'fa fa-envelope';
    protected $objectTrans  = 'account';

    public function createCustomRecord($parameters)
    {
        $parameters['outgoingSecures']  = [(object)['id' => '', 'name' => trans('pulsar::pulsar.no_security')], (object)['id' => 'ssl', 'name' => 'SSL'], (object)['id' => 'tls', 'name' => 'TLS'], (object)['id' => 'sslv2', 'name' => 'SSLv2'], (object)['id' => 'sslv3', 'name' => 'SSLv3']];
        $parameters['incomingTypes']    = [(object)['id' => 'imap', 'name' => 'IMAP']];
        $parameters['incomingSecures']  = [(object)['id' => '', 'name' => trans('pulsar::pulsar.no_security')], (object)['id' => 'ssl', 'name' => 'SSL']];

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        $account = [
            'name_013'              => Request::input('name'),
            'email_013'             => Request::input('email'),
            'reply_to_013'          => Request::input('replyTo') == ""? null : Request::get('replyTo'),
            'outgoing_server_013'   => Request::input('outgoingServer'),
            'outgoing_user_013'     => Request::input('outgoingUser'),
            'outgoing_pass_013'     => Crypt::encrypt(Request::input('outgoingPass')),
            'outgoing_secure_013'   => Request::input('outgoingSecure'),
            'outgoing_port_013'     => Request::input('outgoingPort'),
            'incoming_type_013'     => Request::input('incomingType'),
            'incoming_server_013'   => Request::input('incomingServer'),
            'incoming_user_013'     => Request::input('incomingUser'),
            'incoming_pass_013'     => Crypt::encrypt(Request::input('incomingPass')),
            'incoming_secure_013'   => Request::input('incomingSecure'),
            'incoming_port_013'     => Request::input('incomingPort'),
            'n_emails_013'          => 0
        ];

        $response = EmailServices::testEmailAccount($account);

        if($response === true)
        {
            EmailAccount::create($account);
        }
        else
        {
            return redirect()->route('create' . $this->routeSuffix, $parameters['urlParameters'])->withErrors($response)->withInput();
        }
    }

    public function editCustomRecord($parameters)
    {
        $parameters['outgoingSecures']  = [(object)['id' => '', 'name' => trans('pulsar::pulsar.no_security')], (object)['id' => 'ssl', 'name' => 'SSL'], (object)['id' => 'tls', 'name' => 'TLS'], (object)['id' => 'sslv2', 'name' => 'SSLv2'], (object)['id' => 'sslv3', 'name' => 'SSLv3']];
        $parameters['incomingTypes']    = [(object)['id' => 'imap', 'name' => 'IMAP']];
        $parameters['incomingSecures']  = [(object)['id' => '', 'name' => trans('pulsar::pulsar.no_security')], (object)['id' => 'ssl', 'name' => 'SSL']];

        return $parameters;
    }

    public function checkSpecialRulesToUpdate($parameters)
    {
        $parameters['specialRules']['outgoingPassRule'] = Request::has('outgoingPass')? false : true;
        $parameters['specialRules']['incomingPassRule'] = Request::has('incomingPass')? false : true;

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        $account = [
            'name_013'              => Request::input('name'),
            'email_013'             => Request::input('email'),
            'reply_to_013'          => Request::input('replyTo') == ""? null : Request::get('replyTo'),
            'outgoing_server_013'   => Request::input('outgoingServer'),
            'outgoing_user_013'     => Request::input('outgoingUser'),
            'outgoing_secure_013'   => Request::input('outgoingSecure'),
            'outgoing_port_013'     => Request::input('outgoingPort'),
            'incoming_type_013'     => Request::input('incomingType'),
            'incoming_server_013'   => Request::input('incomingServer'),
            'incoming_user_013'     => Request::input('incomingUser'),
            'incoming_secure_013'   => Request::input('incomingSecure'),
            'incoming_port_013'     => Request::input('incomingPort')
        ];

        // Get object to read password to check account
        if($parameters['specialRules']['outgoingPassRule'] || $parameters['specialRules']['incomingPassRule'])
        {
            $oldAccount = EmailAccount::find(Request::input('id'));
        }

        if(!$parameters['specialRules']['outgoingPassRule'])
        {
            $account['outgoing_pass_013'] = Crypt::encrypt(Request::input('outgoingPass'));
        }
        else
        {
            $account['outgoing_pass_013'] = $oldAccount->outgoing_pass_013;
        }

        if(!$parameters['specialRules']['incomingPassRule'])
        {
            $account['incoming_pass_013'] = Crypt::encrypt(Request::input('incomingPass'));
        }
        else
        {
            $account['incoming_pass_013'] = $oldAccount->incoming_pass_013;
        }

        $response = EmailServices::testEmailAccount($account);

        if($response === true)
        {
            EmailAccount::where('id_013', Request::input('id'))->update($account);
        }
        else
        {
            return redirect()->route('edit' . $this->routeSuffix, $parameters['urlParameters'])->withErrors($response)->withInput();
        }
    }
}