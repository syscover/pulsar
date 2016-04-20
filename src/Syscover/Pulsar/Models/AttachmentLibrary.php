<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class AttachmentLibrary
 *
 * Model with properties
 * <br><b>[id, resource, url, file_name, mime, size, type, type_text, width, height, data]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class AttachmentLibrary extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_014_attachment_library';
    protected $primaryKey   = 'id_014';
    protected $suffix       = '014';
    public $timestamps      = false;
    protected $fillable     = ['id_014', 'resource_014', 'url_014', 'file_name_014', 'mime_014', 'size_014', 'type_014', 'type_text_014', 'width_014', 'height_014', 'data_014'];
    protected $maps         = [];
    protected $relationMaps = [
        'resource'   => \Syscover\Pulsar\Models\Resource::class,
    ];
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query->join('001_007_resource', '001_014_attachment_library.resource_014', '=', '001_007_resource.id_007')
            ->join('001_012_package', '001_007_resource.package_007', '=', '001_012_package.id_012');
    }

    public function getResource()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Resource', 'resource_014');
    }
}