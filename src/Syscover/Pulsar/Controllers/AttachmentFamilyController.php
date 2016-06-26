<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\AttachmentFamily;

/**
 * Class AttachmentFamilyController
 * @package Syscover\Pulsar\Controllers
 */

class AttachmentFamilyController extends Controller
{
    protected $routeSuffix  = 'attachmentFamily';
    protected $folder       = 'attachment_family';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_015', 'name_007', 'name_015', 'width_015', 'height_015'];
    protected $nameM        = 'name_015';
    protected $model        = AttachmentFamily::class;
    protected $icon         = 'fa fa-th';
    protected $objectTrans  = 'attachment_family';

    public function createCustomRecord($parameters)
    {
        $parameters['resources'] = Resource::builder()
            ->where('active_012', true)
            ->whereIn('id_007', config('pulsar.resourcesAttachments'))
            ->get();

        return $parameters;
    }

    public function storeCustomRecord($parameters)
    {
        AttachmentFamily::create([
            'resource_id_015'   => $this->request->input('resource'),
            'name_015'          => $this->request->input('name'),
            'width_015'         => $this->request->has('width')? $this->request->input('width') : null,
            'height_015'        => $this->request->has('height')? $this->request->input('height') : null,
            'data_015'          => null
        ]);
    }

    public function editCustomRecord($parameters)
    {
        $parameters['resources'] = Resource::builder()
            ->where('active_012', true)
            ->whereIn('id_007', config('pulsar.resourcesAttachments'))
            ->get();

        return $parameters;
    }
    
    public function updateCustomRecord($parameters)
    {
        AttachmentFamily::where('id_015', $parameters['id'])->update([
            'resource_id_015'   => $this->request->input('resource'),
            'name_015'          => $this->request->input('name'),
            'width_015'         => $this->request->has('width')? $this->request->input('width') : null,
            'height_015'        => $this->request->has('height')? $this->request->input('height') : null,
            'data_015'          => null
        ]);
    }
}