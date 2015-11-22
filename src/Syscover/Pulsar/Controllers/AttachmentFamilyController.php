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

use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\AttachmentFamily;

class AttachmentFamilyController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'attachmentFamily';
    protected $folder       = 'attachment_family';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_015', 'name_007', 'name_015'];
    protected $nameM        = 'name_015';
    protected $model        = '\Syscover\Pulsar\Models\AttachmentFamily';
    protected $icon         = 'fa fa-th';
    protected $objectTrans  = 'attachment_family';

    public function createCustomRecord($request, $parameters)
    {
        $parameters['resources'] = Resource::getResources(['active_012' => true]);

        return $parameters;
    }

    public function storeCustomRecord($request, $parameters)
    {
        AttachmentFamily::create([
            'resource_015'  => $request->input('resource'),
            'name_015'      => $request->input('name'),
            'width_015'     => $request->has('width')? $request->input('width') : null,
            'height_015'    => $request->has('height')? $request->input('height') : null,
            'data_015'      => null
        ]);
    }

    public function editCustomRecord($request, $parameters)
    {
        $parameters['resources'] = Resource::getResources(['active_012' => true]);

        return $parameters;
    }
    
    public function updateCustomRecord($request, $parameters)
    {
        AttachmentFamily::where('id_015', $parameters['id'])->update([
            'resource_015'  => $request->input('resource'),
            'name_015'      => $request->input('name'),
            'width_015'     => $request->has('width')? $request->input('width') : null,
            'height_015'    => $request->has('height')? $request->input('height') : null,
            'data_015'      => null
        ]);
    }
}