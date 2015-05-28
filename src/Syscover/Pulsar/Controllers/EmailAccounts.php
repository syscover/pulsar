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

use Illuminate\Support\Facades\Request;
use Syscover\Pulsar\Traits\ControllerTrait;
use Syscover\Pulsar\Models\EmailAccount;

class EmailAccounts extends Controller {

    use ControllerTrait;

    protected $routeSuffix  = 'EmailAccount';
    protected $folder       = 'email_accounts';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_013', 'name_013', ['data' => 'email_013', 'type' => 'email']];
    protected $nameM        = 'name_013';
    protected $model        = '\Syscover\Pulsar\Models\EmailAccount';
    protected $icon         = 'icon-envelope';
    protected $objectTrans  = 'account';

    public function createCustomRecord($parameters)
    {
        $parameters['incomingTypes'] = [(object)['id' => 'imap', 'name' => 'IMAP']];
        $parameters['incomingSecures'] = [(object)['id' => '', 'name' => 'No security'], (object)['id' => 'ssl', 'name' => 'SSL']];

        return $parameters;
    }

    public function storeCustomRecord()
    {
        $contact = EmailAccount::create([
            'company_013'       => Request::input('company'),
            'name_013'          => Request::input('name'),
            'surname_013'       => Request::input('surname'),
            'birthdate_013'     => Request::has('birthdate')? \DateTime::createFromFormat('d-m-Y',Request::input('birthdate'))->getTimestamp() : null,
            'country_013'       => Request::input('country'),
            'prefix_013'        => Request::input('prefix'),
            'mobile_013'        => Request::has('mobile')? str_replace('-', '', Request::input('mobile')) : null,
            'email_013'         => strtolower(Request::input('email')),
        ]);

        $contact->groups()->attach(Request::input('groups'));
    }

    public function checkSpecialRulesToUpdate($parameters)
    {
        $contact = Contact::find($parameters['id']);

        $parameters['specialRules']['emailRule'] = Request::input('email') == $contact->email_013? true : false;
        $parameters['specialRules']['mobileRule'] = Request::input('mobile') == $contact->mobile_013? true : false;

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        Contact::where('id_013', $parameters['id'])->update([
            'company_013'       => Request::input('company'),
            'name_013'          => Request::input('name'),
            'surname_013'       => Request::input('surname'),
            'birthdate_013'     => Request::has('birthdate')? \DateTime::createFromFormat('d-m-Y',Request::input('birthdate'))->getTimestamp() : null,
            'country_013'       => Request::input('country'),
            'prefix_013'        => Request::input('prefix'),
            'mobile_013'        => Request::has('mobile')? str_replace('-', '', Request::input('mobile')) : null,
            'email_013'         => strtolower(Request::input('email')),
        ]);
    }
}