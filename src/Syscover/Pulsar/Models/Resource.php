<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Resource
 *
 * Model with properties
 * <br><b>[id, name, package_id]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Resource extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_007_resource';
    protected $primaryKey   = 'id_007';
    public $incrementing    = false;
    protected $suffix       = '007';
    public $timestamps      = false;
    protected $fillable     = ['id_007', 'name_007', 'package_id_007'];
    protected $maps         = [];
    protected $relationMaps = [
        'package'      => Package::class,
    ];
    private static $rules   = [
        'id'        =>  'required|between:2,30|unique:mysql2.001_007_resource,id_007',
        'module'    =>  'not_in:null',
        'name'      =>  'required|between:2,50'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule']) static::$rules['id'] = 'required|between:2,30';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_012_package', '001_007_resource.package_id_007', '=', '001_012_package.id_012');
    }

    public function getPackage()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Package', 'package_id_007');
    }

    public static function getRecords($args)
    {
        $query =  Resource::join('001_012_package', '001_007_resource.package_id_007', '=', '001_012_package.id_012');

        if(isset($args['active_012']))  $query->where('active_012', $args['active_012']);
        if(isset($args['whereIn']))     $query->whereIn($args['whereIn']['column'], $args['whereIn']['ids']);

        return $query->get();
    }
}