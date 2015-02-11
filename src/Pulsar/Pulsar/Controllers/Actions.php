<?php namespace Pulsar\Pulsar\Controllers;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Pulsar\Pulsar\Models\Action;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Actions extends BaseController
{
    use ControllerTrait;

    protected $resource = 'admin-pass-actions';
    protected $route    = 'actions';
    protected $folder   = 'actions';
    protected $package  = 'pulsar';
    protected $aColumns = ['id_008','name_008'];
    protected $nameM    = 'name_008';
    protected $model    = '\Pulsar\Pulsar\Models\Action';

    public function store($offset = 0)
    {
        $validation = Action::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createAction', $offset)->withErrors($validation)->withInput();
        }
        else
        {
            Action::create(array(
                'id_008'    => Input::get('id'),
                'name_008'  => Input::get('name')
            )); 

            return Redirect::route('actions', $offset)->with([
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.message_log_recorded', ['name' => Input::get('name')])
            ]);
        }
    }
    
    public function update($offset = 0)
    {
        if(Input::get('id') == Input::get('idOld')) $idRule = false; else $idRule = true;
        
        $validation = Action::validate(Input::all(), $idRule);
        
        if ($validation->fails())
        {
            return Redirect::route('editAction', [Input::get('id'), $offset])->withErrors($validation);
        }
        else
        {
            Action::where('id_008', Input::get('idOld'))->update([
                'id_008'    => Input::get('id'),
                'name_008'  => Input::get('name')
            ]);

            return Redirect::route('actions', $offset)->with([
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.message_update_record', ['name' => Input::get('name')])
            ]);
        }
    }
}