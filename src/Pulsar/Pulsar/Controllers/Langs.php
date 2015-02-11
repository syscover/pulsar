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

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Pulsar\Pulsar\Models\Lang;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Langs extends BaseController {

    use ControllerTrait;

    protected $resource = 'admin-lang';
    protected $route    = 'langs';
    protected $folder   = 'langs';
    protected $package  = 'pulsar';
    protected $aColumns = ['id_001', 'image_001', 'name_001', 'base_001', 'active_001', 'sorting_001'];
    protected $nameM    = 'name_001';
    protected $model    = '\Pulsar\Pulsar\Models\Lang';

    public function jsonDataCustomColumn($aObject, $aColumn)
    {
        if($aColumn == "image_001")
        {
            if($aObject[$aColumn] != '')
                return '<img src="' . asset('/packages/pulsar/pulsar/storage/languages/' . $aObject[$aColumn]) . '">';
            else
                return '';
        }
        elseif($aColumn == "base_001")
        {
            if($aObject[$aColumn] == 1)
                return '<i class="icomoon-icon-checkmark-3"></i>';
            else
                return '';
        }
        elseif($aColumn == "active_001")
        {
            if($aObject[$aColumn] == 1)
                return '<i class="icomoon-icon-checkmark-3"></i>';
            else
                return '<i class="icomoon-icon-blocked"></i>';
        }
        else
        {
            return $aObject[$aColumn];
        }
    }

    
    public function store($offset = 0)
    {
        $validation = Lang::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createLang', $offset)->withErrors($validation)->withInput();
        }
        else
        {
            $filename = Miscellaneous::uploadFiles('imagen', public_path().'/packages/pulsar/pulsar/storage/languages', false, Input::get('id'));

            if(Input::get('base'))
            {
                Lang::resetIdiomaBase();
            }
                       
            Lang::create([
                'id_001'        => Input::get('id'),
                'name_001'      => Input::get('name'),
                'image_001'     => $filename,
                'sorting_001'   => Input::get('orden'),
                'base_001'      => Input::get('base',0),
                'active_001'    => Input::get('activo',0)
            ]);
            
            if(Input::get('base')){
                Session::put('idiomaBase', Lang::getIdiomaBase());
            }

            return Redirect::route('languages', $offset)->with(array(
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.message_log_recorded', ['nombre' => Input::get('nombre')])
            ));
        }
    }
    
    public function editCustomRecord($data)
    {
        $data['javascriptView']     = 'pulsar::languages.js.edit';

        return $data;
    }
    
    public function update($inicio=0)
    {
        if(!Session::get('userAcl')->isAllowed(Auth::user()->profile_010,$this->resource,'edit')) App::abort(403, 'Permission denied.');
        
        //comprobamos las reglas a cumplir dependiendo de los cambios realizados
        if(Input::hasFile('imagen')) $imageRule = true; else $imageRule = false;
        if(Input::get('id') == Input::get('idOld')) $idRule = false; else $idRule = true;
        
        $validation = Lang::validate(Input::all(), $imageRule, $idRule);
                
        if ($validation->fails())
        {
            return Redirect::route('editIdioma',array(Input::get('idOld'), $inicio))->withErrors($validation);
        }
        else
        {
            if(Input::hasFile('imagen'))
            {
                $filename = Miscellaneous::uploadFiles('imagen', public_path().'/packages/pulsar/pulsar/storage/languages', false, Input::get('id'));
            }
            else
            {
                // we change file name to be the same name that new ID
                if($idRule)
                {
                    $filename = Input::get('id') . '.' . File::extension(Input::get('imagen'));
                    File::move(public_path() . '/packages/pulsar/pulsar/storage/languages/' . Input::get('imagen'), public_path() . '/packages/pulsar/pulsar/storage/languages/' . $filename);
                }
                else
                {
                    $filename = Input::get('imagen');
                }
            }
            
            if(Input::get('base'))
            {
                Lang::resetIdiomaBase();
            }
            
            Lang::where('id_001','=',Input::get('idOld'))->update(array(
                'id_001'        => Input::get('id'),
                'name_001'      => Input::get('nombre'),
                'image_001'     => $filename,
                'sorting_001'   => Input::get('orden'),
                'base_001'      => Input::get('base',0),
                'active_001'    => Input::get('activo',0)
            ));

            if(Input::get('base'))
            {
                Session::put('idiomaBase', Lang::getIdiomaBase());
            }

            return Redirect::route('languages', array($inicio))->with(array(
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.aviso_actualiza_registro', array('nombre' => Input::get('nombre')))
            ));
        }
    }
    
    public function destroyCustomRecord($object)
    {
        File::delete(public_path() . '/packages/pulsar/pulsar/storage/languages/' . $object->image_001);
    }
    
    public function destroyCustomRecords($ids)
    {
        $objects = Lang::getRecordsById($ids);
        
        foreach ($objects as $object)
        {
            File::delete(public_path() . '/packages/pulsar/pulsar/storage/languages/' . $object->image_001);
        }
    }

    public function deleteImage($id)
    {
        $object = Lang::find($id);

        File::delete(public_path() . '/packages/pulsar/pulsar/storage/languages/' . $object->image_001);

        Lang::where('id_001', $id)->update(array(
            'image_001' => null,
        ));
    }
}