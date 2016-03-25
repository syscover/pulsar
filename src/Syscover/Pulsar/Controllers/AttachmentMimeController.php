<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\AttachmentMime;
use Syscover\Pulsar\Models\Resource;

/**
 * Class AttachmentsMimeController
 * @package Syscover\Pulsar\Controllers
 */

class AttachmentMimeController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'attachmentMime';
    protected $folder       = 'attachment_mime';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_019', 'resource_id_019', 'name_007', 'mime_019'];
    protected $nameM        = 'mime_019';
    protected $model        = AttachmentMime::class;
    protected $icon         = 'fa fa-file';
    protected $objectTrans  = 'attachment_mime';

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
        AttachmentMime::create([
            'resource_id_019'   => $this->request->input('resource'),
            'mime_019'          => $this->request->input('mime')
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
        AttachmentMime::where('id_019', $parameters['id'])->update([
            'resource_id_019'   => $this->request->input('resource'),
            'mime_019'          => $this->request->input('mime')
        ]);
    }
}