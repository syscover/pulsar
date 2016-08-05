<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Package
 *
 * Model with properties
 * <br><b>[id, name, folder, active, sorting]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Package extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_012_package';
    protected $primaryKey   = 'id_012';
    protected $suffix        = '012';
    public $timestamps      = false;
    protected $fillable     = ['id_012', 'name_012', 'folder_012', 'active_012', 'sorting_012'];
    protected $maps         = [];
    protected $relationMaps = [];
    protected $casts        = ['active_012' => 'boolean'];
    private static $rules   = [
        'name'    =>  'required|between:2,50',
        'folder'  =>  'required|between:2,50'
    ];
        
    public static function validate($data, $specialRules = [])
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query;
    }

    public static function getRecords($args)
    {
        $query = Package::query();

        if(isset($args['active_012']))  $query->where('active_012', $args['active_012']);
        if(isset($args['orderBy']))     $query->orderBy($args['orderBy']['column'], $args['orderBy']['order']);

        return $query->get();
    }
}