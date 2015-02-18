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
use Illuminate\Support\Facades\File;
use Pulsar\Pulsar\Libraries\Miscellaneous;
use Pulsar\Pulsar\Models\Lang;
use Pulsar\Pulsar\Traits\ControllerTrait;

class Langs extends BaseController {

    use ControllerTrait;

    protected $resource     = 'admin-lang';
    protected $routeSuffix  = 'Lang';
    protected $folder       = 'langs';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_001', ['name' =>'image_001', 'type' => 'img', 'url' => '/packages/pulsar/pulsar/storage/langs/'], 'name_001', ['name' => 'base_001', 'type' => 'check'], ['name' => 'active_001', 'type' => 'active'], 'sorting_001'];
    protected $nameM        = 'name_001';
    protected $model        = '\Pulsar\Pulsar\Models\Lang';
    protected $icon         = 'brocco-icon-flag';
    protected $objectTrans  = 'language';


    public function storeCustomRecord()
    {
        $filename = Miscellaneous::uploadFiles('image', public_path() . '/packages/pulsar/pulsar/storage/langs');

        if(Input::get('base')) Lang::resetBaseLang();

        Lang::create([
            'id_001'        => Input::get('id'),
            'name_001'      => Input::get('name'),
            'image_001'     => $filename,
            'sorting_001'   => Input::get('sorting'),
            'base_001'      => Input::get('base', 0),
            'active_001'    => Input::get('active', 0)
        ]);

        if(Input::get('base')) Session::put('baseLang', Lang::getBaseLang());
    }

    public function updateCustomRecord($id)
    {
        if(Input::hasFile('image'))
        {
            $filename = Miscellaneous::uploadFiles('image', public_path() . '/packages/pulsar/pulsar/storage/langs');
        }
        else
        {
            $filename = Input::get('image');
        }

        if(Input::get('base')) Lang::resetBaseLang();

        Lang::where('id_001', $id)->update([
            'id_001'        => Input::get('id'),
            'name_001'      => Input::get('name'),
            'image_001'     => $filename,
            'sorting_001'   => Input::get('sorting'),
            'base_001'      => Input::get('base', 0),
            'active_001'    => Input::get('active', 0)
        ]);

        if(Input::get('base')) Session::put('baseLang', Lang::getBaseLang());
    }
    
    public function deleteCustomRecord($object)
    {
        File::delete(public_path() . '/packages/pulsar/pulsar/storage/langs/' . $object->image_001);
    }
    
    public function deleteCustomRecords($ids)
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