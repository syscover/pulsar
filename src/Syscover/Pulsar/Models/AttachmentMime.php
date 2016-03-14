<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class AttachmentMime
 *
 * Model with properties
 * <br><b>[id, resource_id, mime]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class AttachmentMime extends Model
{
    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_019_attachment_mime';
    protected $primaryKey   = 'id_019';
    public $incrementing    = false;
    protected $suffix       = '019';
    public $timestamps      = false;
    protected $fillable     = ['id_019', 'resource_id_019', 'mime_019'];
    protected $maps         = [];
    protected $relationMaps = [
        'resource'      => Resource::class,
    ];
    private static $rules   = [
        'resource'  =>  'required|between:2,30',
        'mime'      =>  'required|between:2,255'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder()
    {
        return AttachmentMime::join('001_007_resource', '001_019_attachment_mime.resource_id_019', '=', '001_007_resource.id_007');
    }

    public function getResource()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Resource', 'resource_id_019');
    }

    public static function addToGetIndexRecords($parameters)
    {
        return AttachmentMime::builder();
    }
}