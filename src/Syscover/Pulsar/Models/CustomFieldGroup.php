<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class CustomFieldGroup
 *
 * Model with properties
 * <br><b>[id, name, resource, data]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class CustomFieldGroup extends Model
{
    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_025_field_group';
    protected $primaryKey   = 'id_025';
    protected $suffix       = '025';
    public $timestamps      = false;
    protected $fillable     = ['id_025', 'name_025', 'resource_025', 'data_025'];
    protected $maps         = [];
    protected $relationMaps = [
        'resource'   => \Syscover\Pulsar\Models\Resource::class,
    ];
    private static $rules   = [
        'name'      => 'required|between:2,100',
        'resource'  => 'required',
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_007_resource', '001_025_field_group.resource_025', '=', '001_007_resource.id_007');
    }

    public static function addToGetIndexRecords($parameters)
    {
        $query =  CustomFieldGroup::builder();

        return $query;
    }

    /**
     * @deprecated
     * @param $args
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getRecords($args)
    {
        $query = CustomFieldGroup::query();

        if(isset($args['resource_025'])) $query->where('resource_025', $args['resource_025']);

        return $query->get();
    }
}