<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Illuminate\Support\Facades\Crypt;
use Syscover\Pulsar\Libraries\EmailServices;
use Syscover\Pulsar\Models\EmailAccount;

/**
 * Class EmailAccountController
 * @package Syscover\Pulsar\Controllers
 */

class EmailAccountController extends Controller
{
    protected $routeSuffix  = 'emailAccount';
    protected $folder       = 'email_account';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_013', 'name_013', ['data' => 'email_013', 'type' => 'email']];
    protected $nameM        = 'name_013';
    protected $model        = EmailAccount::class;
    protected $icon         = 'fa fa-envelope';
    protected $objectTrans  = 'account';

    public function createCustomRecord($parameters)
    {
        $parameters['outgoingSecures']  = [
            (object)['id' => '', 'name' => trans('pulsar::pulsar.no_security')],
            (object)['id' => 'ssl', 'name' => 'SSL'],
            (object)['id' => 'tls', 'name' => 'TLS'],
            (object)['id' => 'sslv2', 'name' => 'SSLv2'],
            (object)['id' => 'sslv3', 'name' => 'SSLv3']
        ];
        $parameters['incomingTypes']    = [
            (object)['id' => 'imap', 'name' => 'IMAP']
        ];
        $parameters['incomingSecures']  = [
            (object)['id' => '', 'name' => trans('pulsar::pulsar.no_security')],
            (object)['id' => 'ssl', 'name' => 'SSL']
        ];

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        $account = [
            'name_013'              => $this->request->input('name'),
            'email_013'             => $this->request->input('email'),
            'reply_to_013'          => empty($this->request->input('replyTo'))? null : $this->request->input('replyTo'),
            'outgoing_server_013'   => $this->request->input('outgoingServer'),
            'outgoing_user_013'     => $this->request->input('outgoingUser'),
            'outgoing_pass_013'     => Crypt::encrypt($this->request->input('outgoingPass')),
            'outgoing_secure_013'   => $this->request->input('outgoingSecure'),
            'outgoing_port_013'     => $this->request->input('outgoingPort'),
            'incoming_type_013'     => $this->request->input('incomingType'),
            'incoming_server_013'   => $this->request->input('incomingServer'),
            'incoming_user_013'     => $this->request->input('incomingUser'),
            'incoming_pass_013'     => Crypt::encrypt($this->request->input('incomingPass')),
            'incoming_secure_013'   => $this->request->input('incomingSecure'),
            'incoming_port_013'     => $this->request->input('incomingPort'),
            'n_emails_013'          => 0
        ];

        $response = EmailServices::testEmailAccount($account);

        if($response === true)
        {
            EmailAccount::create($account);
        }
        else
        {
            return redirect()->route('create' . ucfirst($this->routeSuffix), $parameters['urlParameters'])->withErrors($response)->withInput();
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
        $parameters['specialRules']['outgoingPassRule'] = $this->request->has('outgoingPass')? false : true;
        $parameters['specialRules']['incomingPassRule'] = $this->request->has('incomingPass')? false : true;

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        $account = [
            'name_013'              => $this->request->input('name'),
            'email_013'             => $this->request->input('email'),
            'reply_to_013'          => empty($this->request->input('replyTo'))? null : $this->request->input('replyTo'),
            'outgoing_server_013'   => $this->request->input('outgoingServer'),
            'outgoing_user_013'     => $this->request->input('outgoingUser'),
            'outgoing_secure_013'   => $this->request->input('outgoingSecure'),
            'outgoing_port_013'     => $this->request->input('outgoingPort'),
            'incoming_type_013'     => $this->request->input('incomingType'),
            'incoming_server_013'   => $this->request->input('incomingServer'),
            'incoming_user_013'     => $this->request->input('incomingUser'),
            'incoming_secure_013'   => $this->request->input('incomingSecure'),
            'incoming_port_013'     => $this->request->input('incomingPort')
        ];

        // Get object to read password to check account
        if($parameters['specialRules']['outgoingPassRule'] || $parameters['specialRules']['incomingPassRule'])
        {
            $oldAccount = EmailAccount::find($this->request->input('id'));
        }

        if(!$parameters['specialRules']['outgoingPassRule'])
        {
            $account['outgoing_pass_013'] = Crypt::encrypt($this->request->input('outgoingPass'));
        }
        else
        {
            $account['outgoing_pass_013'] = $oldAccount->outgoing_pass_013;
        }

        if(!$parameters['specialRules']['incomingPassRule'])
        {
            $account['incoming_pass_013'] = Crypt::encrypt($this->request->input('incomingPass'));
        }
        else
        {
            $account['incoming_pass_013'] = $oldAccount->incoming_pass_013;
        }

        $response = EmailServices::testEmailAccount($account);

        if($response === true)
        {
            EmailAccount::where('id_013', $this->request->input('id'))->update($account);
        }
        else
        {
            return redirect()->route('edit' . ucfirst($this->routeSuffix), $parameters['urlParameters'])->withErrors($response)->withInput();
        }
    }
}