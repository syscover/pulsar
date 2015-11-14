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
use Illuminate\Support\Facades\File;
use Syscover\Pulsar\Libraries\Miscellaneous;
use Syscover\Pulsar\Models\Lang;
use Syscover\Pulsar\Traits\TraitController;

class LangController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'Lang';
    protected $folder       = 'lang';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_001', ['data' =>'image_001', 'type' => 'img', 'url' => '/packages/syscover/pulsar/storage/langs/'], 'name_001', ['data' => 'base_001', 'type' => 'check'], ['data' => 'active_001', 'type' => 'active'], 'sorting_001'];
    protected $nameM        = 'name_001';
    protected $model        = '\Syscover\Pulsar\Models\Lang';
    protected $icon         = 'fa fa-language';
    protected $objectTrans  = 'language';


    public function storeCustomRecord($request, $parameters)
    {
        $filename = Miscellaneous::uploadFiles('image', public_path() . '/packages/syscover/pulsar/storage/langs');

        if(Request::input('base')) Lang::resetBaseLang();

        Lang::create([
            'id_001'        => Request::input('id'),
            'name_001'      => Request::input('name'),
            'image_001'     => $filename,
            'sorting_001'   => Request::input('sorting'),
            'base_001'      => Request::input('base', 0),
            'active_001'    => Request::input('active', 0)
        ]);

        if(Request::input('base')) session(['baseLang' => Lang::getBaseLang()]);
    }

    public function checkSpecialRulesToUpdate($request, $parameters)
    {
        if(Request::hasFile('image'))
        {
            $parameters['specialRules']['imageRule'] = true;
        }

        return $parameters;
    }

    public function updateCustomRecord($request, $parameters)
    {
        if(Request::hasFile('image'))
        {
            $filename = Miscellaneous::uploadFiles('image', public_path() . '/packages/syscover/pulsar/storage/langs');
        }
        else
        {
            $filename = Request::input('image');
        }

        if(Request::input('base')) Lang::resetBaseLang();

        Lang::where('id_001', $parameters['id'])->update([
            'id_001'        => Request::input('id'),
            'name_001'      => Request::input('name'),
            'image_001'     => $filename,
            'sorting_001'   => Request::input('sorting'),
            'base_001'      => Request::input('base', 0),
            'active_001'    => Request::input('active', 0)
        ]);

        if(Request::input('base')) session(['baseLang' => Lang::getBaseLang()]);
    }
    
    public function deleteCustomRecord($request, $object)
    {
        File::delete(public_path() . '/packages/syscover/pulsar/storage/langs/' . $object->image_001);
    }
    
    public function deleteCustomRecords($request, $ids)
    {
        $objects = Lang::getRecordsById($ids);
        
        foreach ($objects as $object)
        {
            File::delete(public_path() . '/packages/syscover/pulsar/storage/langs/' . $object->image_001);
        }
    }

    public function ajaxDeleteImage($id)
    {
        $object = Lang::find($id);

        File::delete(public_path() . '/packages/syscover/pulsar/storage/langs/' . $object->image_001);

        Lang::where('id_001', $id)->update([
            'image_001' => null,
        ]);
    }
}