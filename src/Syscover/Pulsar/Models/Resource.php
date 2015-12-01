<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class Resource
 *
 * Model with properties
 * <br><b>[id, name, package]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Resource extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_007_resource';
    protected $primaryKey   = 'id_007';
    public $timestamps      = false;
    protected $fillable     = ['id_007', 'name_007', 'package_007'];
    protected $maps = [
        'id'                    => 'id_003',
        'name'                  => 'name_007',
        'package'               => 'package_007',
    ];
    private static $rules   = [
        'id'        =>  'required|between:2,30|unique:001_007_resource,id_007',
        'module'    =>  'not_in:null',
        'name'      =>  'required|between:2,50'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule']) static::$rules['id'] = 'required|between:2,30';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder()
    {
        return Resource::join('001_012_package', '001_007_resource.package_007', '=', '001_012_package.id_012')
            ->newQuery();
    }

    public function package()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Package', 'package_007');
    }

    public static function addToGetRecordsLimit()
    {
        return Resource::join('001_012_package', '001_007_resource.package_007', '=', '001_012_package.id_012')->newQuery();
    }

    public static function getRecords($args)
    {
        $query =  Resource::join('001_012_package', '001_007_resource.package_007', '=', '001_012_package.id_012')->newQuery();

        if(isset($args['active_012']))  $query->where('active_012', $args['active_012']);
        if(isset($args['whereIn']))     $query->whereIn($args['whereIn']['column'], $args['whereIn']['ids']);

        return $query->get();
    }
}