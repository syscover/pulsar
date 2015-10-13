<?php namespace Syscover\Pulsar\Controllers;

/**
 * @package	    Cms
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Support\Facades\Request;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\AttachmentFamily;

class AttachmentFamilyController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'AttachmentFamily';
    protected $folder       = 'attachment_family';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_015', 'name_007', 'name_015'];
    protected $nameM        = 'name_015';
    protected $model        = '\Syscover\Pulsar\Models\AttachmentFamily';
    protected $icon         = 'fa fa-th';
    protected $objectTrans  = 'attachment_family';

    public function createCustomRecord($parameters)
    {
        $parameters['resources'] = Resource::getResources(['active_012' => true]);

        return $parameters;
    }

    public function storeCustomRecord()
    {
        AttachmentFamily::create([
            'resource_015'  => Request::input('resource'),
            'name_015'      => Request::input('name'),
            'width_015'     => Request::has('width')? Request::input('width') : null,
            'height_015'    => Request::has('height')? Request::input('height') : null,
            'data_015'      => null
        ]);
    }

    public function editCustomRecord($parameters)
    {
        $parameters['resources'] = Resource::getResources(['active_012' => true]);

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        AttachmentFamily::where('id_015', $parameters['id'])->update([
            'resource_015'  => Request::input('resource'),
            'name_015'      => Request::input('name'),
            'width_015'     => Request::has('width')? Request::input('width') : null,
            'height_015'    => Request::has('height')? Request::input('height') : null,
            'data_015'      => null
        ]);
    }
}