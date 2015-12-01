<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class AttachmentLibrary
 *
 * Model with properties
 * <br><b>[id, resource, url, file_name, mime, size, type, type_text, width, height, data]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class AttachmentLibrary extends Model {

    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_014_attachment_library';
    protected $primaryKey   = 'id_014';
    protected $sufix        = '014';
    public $timestamps      = false;
    protected $fillable     = ['id_014', 'resource_014', 'url_014', 'file_name_014', 'mime_014', 'size_014', 'type_014', 'type_text_014', 'width_014', 'height_014', 'data_014'];
    protected $maps = [
        'id'                    => 'id_014',
        'resource'              => 'resource_014',
        'url'                   => 'url_014',
        'file_name'             => 'file_name_014',
        'mime'                  => 'mime_014',
        'size'                  => 'size_014',
        'type'                  => 'type_014',
        'type_text'             => 'type_text_014',
        'width'                 => 'width_014',
        'height'                => 'height_014',
        'data'                  => 'data_014',
    ];
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function resource()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Resource', 'resource_014');
    }

    public static function addToGetRecordsLimit($parameters)
    {
        $query =  AttachmentLibrary::join('001_007_resource', '001_014_attachment_library.resource_014', '=', '001_007_resource.id_007')
            ->join('001_012_package', '001_007_resource.package_007', '=', '001_012_package.id_012')
            ->newQuery();

        return $query;
    }
}