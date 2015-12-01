<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class AttachmentFamily
 *
 * Model with properties
 * <br><b>[id, lang, resource, object, family, library, library_file_name, sorting, name, file_name, mime, size, type, type_text, width, height, data_lang, data]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class AttachmentFamily extends Model {

    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_015_attachment_family';
    protected $primaryKey   = 'id_015';
    public $timestamps      = false;
    protected $fillable     = ['id_015', 'resource_015', 'name_015', 'width_015', 'height_015', 'data_015'];
    protected $maps = [
        'id'                    => 'id_015',
        'resource'              => 'resource_015',
        'name'                  => 'name_015',
        'width'                 => 'width_015',
        'height'                => 'height_015',
        'data'                  => 'data_015',
    ];
    private static $rules   = [
        'name'      => 'required|between:2,100',
        'resource'  => 'required',
        'width'     => 'numeric',
        'height'    => 'numeric'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public static function addToGetRecordsLimit($parameters)
    {
        $query =  AttachmentFamily::join('001_007_resource', '001_015_attachment_family.resource_015', '=', '001_007_resource.id_007');

        return $query;
    }

    public static function getAttachmentFamilies($args)
    {
        $query =  AttachmentFamily::query();

        if(isset($args['resource_015'])) $query->where('resource_015', $args['resource_015']);

        return $query->get();
    }
}