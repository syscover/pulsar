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
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\CustomFieldFamily;

class CustomFieldController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'customField';
    protected $folder       = 'field';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_025', 'name_025', 'name_025', 'data_025'];
    protected $nameM        = 'name_025';
    protected $model        = '\Syscover\Pulsar\Models\CustomField';
    protected $icon         = 'fa fa-i-cursor';
    protected $objectTrans  = 'field';

    public function indexCustom($parameters)
    {
        $parameters['urlParameters']['lang'] = session('baseLang');

        return $parameters;
    }

    public function storeCustomRecord($request, $parameters)
    {
        // check if there is id
        if($request->has('id'))
        {
            $id = $request->get('id');
        }
        else
        {
            $id = Field::max('id_151');
            $id++;
        }

        Decoration::create([
            'id_151'        => $id,
            'lang_151'      => $request->input('lang'),
            'name_151'      => $request->input('name'),
            'data_lang_151' => Decoration::addLangDataRecord($id, $request->input('lang'))
        ]);
    }

    public function updateCustomRecord($request, $parameters)
    {
        Decoration::where('id_151', $parameters['id'])->where('lang_151', $request->input('lang'))->update([
            'name_151'  => $request->input('name')
        ]);
    }
}