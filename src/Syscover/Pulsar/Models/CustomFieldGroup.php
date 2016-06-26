<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class CustomFieldGroup
 *
 * Model with properties
 * <br><b>[id, name, resource_id, data]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class CustomFieldGroup extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_025_field_group';
    protected $primaryKey   = 'id_025';
    protected $suffix       = '025';
    public $timestamps      = false;
    protected $fillable     = ['id_025', 'name_025', 'resource_id_025', 'data_025'];
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
        return $query->join('001_007_resource', '001_025_field_group.resource_id_025', '=', '001_007_resource.id_007');
    }

    /**
     * @deprecated
     * @param $args
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getRecords($args)
    {
        $query = CustomFieldGroup::query();

        if(isset($args['resource_id_025'])) $query->where('resource_id_025', $args['resource_id_025']);

        return $query->get();
    }
}