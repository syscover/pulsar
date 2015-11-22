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

use Syscover\Pulsar\Models\CustomField;
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\CustomFieldFamily;

class CustomFieldController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'customField';
    protected $folder       = 'field';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_026', 'name_026', 'name_025'];
    protected $nameM        = 'name_026';
    protected $model        = '\Syscover\Pulsar\Models\CustomField';
    protected $icon         = 'fa fa-i-cursor';
    protected $objectTrans  = 'field';

    public function indexCustom($parameters)
    {
        $parameters['urlParameters']['lang'] = session('baseLang');

        return $parameters;
    }

    public function createCustomRecord($request, $parameters)
    {
        $parameters['families'] = CustomFieldFamily::all();
        return $parameters;
    }

    public function storeCustomRecord($request, $parameters)
    {
        // check if there is id
        if($request->has('id'))
        {
            $id     = $request->input('id');
            $idLang = $id;
        }
        else
        {
            $id = CustomField::max('id_026');
            $id++;
            $idLang = null;
        }

        CustomField::create([
            'id_026'        => $id,
            'family_026'    => $request->input('family'),
            'name_026'      => $request->input('name'),
            'type_026'      => 1,
            'int_value_026' => true,
            'required_026'  => false,
            'data_lang_026' => CustomField::addLangDataRecord($request->input('lang'), $idLang),
            'data_026'      => '',
        ]);
    }

    public function updateCustomRecord($request, $parameters)
    {
        Decoration::where('id_151', $parameters['id'])->where('lang_151', $request->input('lang'))->update([
            'name_151'  => $request->input('name')
        ]);
    }
}