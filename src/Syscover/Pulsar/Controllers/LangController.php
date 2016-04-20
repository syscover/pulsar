<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Illuminate\Support\Facades\File;
use Syscover\Pulsar\Libraries\Miscellaneous;
use Syscover\Pulsar\Models\Lang;

/**
 * Class LangController
 * @package Syscover\Pulsar\Controllers
 */

class LangController extends Controller
{
    protected $routeSuffix  = 'lang';
    protected $folder       = 'lang';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_001', ['data' =>'image_001', 'type' => 'img', 'url' => '/packages/syscover/pulsar/storage/langs/'], 'name_001', ['data' => 'base_001', 'type' => 'check'], ['data' => 'active_001', 'type' => 'active'], 'sorting_001'];
    protected $nameM        = 'name_001';
    protected $model        = Lang::class;
    protected $icon         = 'fa fa-language';
    protected $objectTrans  = 'language';

    public function storeCustomRecord($parameters)
    {
        $filename = Miscellaneous::uploadFiles('image', public_path() . '/packages/syscover/pulsar/storage/langs');

        if($this->request->input('base')) Lang::resetBaseLang();

        Lang::create([
            'id_001'        => $this->request->input('id'),
            'name_001'      => $this->request->input('name'),
            'image_001'     => $filename,
            'sorting_001'   => $this->request->input('sorting'),
            'base_001'      => $this->request->input('base', 0),
            'active_001'    => $this->request->input('active', 0)
        ]);

        if($this->request->input('base')) session(['baseLang' => Lang::getBaseLang()]);
    }

    public function checkSpecialRulesToUpdate($parameters)
    {
        if($this->request->hasFile('image'))
        {
            $parameters['specialRules']['imageRule'] = true;
        }

        return $parameters;
    }

    public function updateCustomRecord($parameters)
    {
        if($this->request->hasFile('image'))
            $filename = Miscellaneous::uploadFiles('image', public_path() . '/packages/syscover/pulsar/storage/langs');
        else
            $filename = $this->request->input('image');

        if($this->request->input('base')) Lang::resetBaseLang();

        Lang::where('id_001', $parameters['id'])->update([
            'id_001'        => $this->request->input('id'),
            'name_001'      => $this->request->input('name'),
            'image_001'     => $filename,
            'sorting_001'   => $this->request->input('sorting'),
            'base_001'      => $this->request->input('base', 0),
            'active_001'    => $this->request->input('active', 0)
        ]);

        if($this->request->input('base')) session(['baseLang' => Lang::getBaseLang()]);
    }
    
    public function deleteCustomRecord($object)
    {
        File::delete(public_path() . '/packages/syscover/pulsar/storage/langs/' . $object->image_001);
    }
    
    public function deleteCustomRecordsSelect($ids)
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