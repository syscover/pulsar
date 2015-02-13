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
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Pulsar\Pulsar\Models\Lang;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Langs extends BaseController {

    use ControllerTrait;

    protected $resource     = 'admin-lang';
    protected $routePrefix  = 'Lang';
    protected $folder       = 'langs';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_001', ['name' =>'image_001', 'type' => 'img', 'url' => '/packages/pulsar/pulsar/storage/langs/'], 'name_001', ['name' => 'base_001', 'type' => 'check'], ['name' => 'active_001', 'type' => 'active'], 'sorting_001'];
    protected $nameM        = 'name_001';
    protected $model        = '\Pulsar\Pulsar\Models\Lang';


    public function store($offset = 0)
    {
        $validation = Lang::validate(Input::all());
              
        if ($validation->fails())
        {
            return Redirect::route('createLang', $offset)->withErrors($validation)->withInput();
        }
        else
        {
            $filename = Miscellaneous::uploadFiles('image', public_path() . '/packages/pulsar/pulsar/storage/langs');

            if(Input::get('base'))
            {
                Lang::resetBaseLang();
            }
                       
            Lang::create([
                'id_001'        => Input::get('id'),
                'name_001'      => Input::get('name'),
                'image_001'     => $filename,
                'sorting_001'   => Input::get('sorting'),
                'base_001'      => Input::get('base', 0),
                'active_001'    => Input::get('active', 0)
            ]);
            
            if(Input::get('base'))
            {
                Session::put('baseLang', Lang::getBaseLang());
            }

            return Redirect::route('langs', $offset)->with(array(
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.message_log_recorded', ['name' => Input::get('name')])
            ));
        }
    }

    public function update($offset = 0)
    {
        $imageRule  = Input::hasFile('image')? true : false;
        $idRule     = Input::get('id') == Input::get('idOld')? false : true;
        
        $validation = Lang::validate(Input::all(), $imageRule, $idRule);
                
        if ($validation->fails())
        {
            return Redirect::route('editLang',array(Input::get('idOld'), $offset))->withErrors($validation);
        }
        else
        {
            if(Input::hasFile('image'))
            {
                $filename = Miscellaneous::uploadFiles('image', public_path() . '/packages/pulsar/pulsar/storage/langs', false, Input::get('id'));
            }
            else
            {
                // we change file name to be the same name that new ID
                if($idRule)
                {
                    $filename = Input::get('id') . '.' . File::extension(Input::get('imagen'));
                    File::move(public_path() . '/packages/pulsar/pulsar/storage/langs/' . Input::get('image'), public_path() . '/packages/pulsar/pulsar/storage/langs/' . $filename);
                }
                else
                {
                    $filename = Input::get('image');
                }
            }
            
            if(Input::get('base'))
            {
                Lang::resetBaseLang();
            }
            
            Lang::where('id_001','=',Input::get('idOld'))->update(array(
                'id_001'        => Input::get('id'),
                'name_001'      => Input::get('name'),
                'image_001'     => $filename,
                'sorting_001'   => Input::get('sorting'),
                'base_001'      => Input::get('base',0),
                'active_001'    => Input::get('active',0)
            ));

            if(Input::get('base'))
            {
                Session::put('baseLang', Lang::getBaseLang());
            }

            return Redirect::route('langs', $offset)->with(array(
                'msg'        => 1,
                'txtMsg'     => trans('pulsar::pulsar.message_update_record', ['name' => Input::get('name')])
            ));
        }
    }
    
    public function destroyCustomRecord($object)
    {
        File::delete(public_path() . '/packages/pulsar/pulsar/storage/langs/' . $object->image_001);
    }
    
    public function destroyCustomRecords($ids)
    {
        $objects = Lang::getRecordsById($ids);
        
        foreach ($objects as $object)
        {
            File::delete(public_path() . '/packages/pulsar/pulsar/storage/langs/' . $object->image_001);
        }
    }

    public function ajaxDeleteImage($id)
    {
        $object = Lang::find($id);

        File::delete(public_path() . '/packages/pulsar/pulsar/storage/langs/' . $object->image_001);

        Lang::where('id_001', $id)->update(array(
            'image_001' => null,
        ));
    }
}