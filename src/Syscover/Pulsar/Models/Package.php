<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class Package
 *
 * Model with properties
 * <br><b>[id, name, folder, active, sorting]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Package extends Model {

    use Eloquence, Mappable;

	protected $table        = '001_012_package';
    protected $primaryKey   = 'id_012';
    protected $sufix        = '012';
    public $timestamps      = false;
    protected $fillable     = ['id_012', 'name_012', 'folder_012', 'active_012', 'sorting_012'];
    protected $maps = [
        'id'                => 'id_012',
        'name'              => 'name_012',
        'folder'            => 'folder_012',
        'active'            => 'active_012',
        'sorting'           => 'sorting_012',
    ];
    protected $casts        = ['active_012' => 'boolean'];
    private static $rules   = [
        'name'    =>  'required|between:2,50',
        'folder'  =>  'required|between:2,50'
    ];
        
    public static function validate($data, $specialRules = [])
    {
        return Validator::make($data, static::$rules);
	}

    public static function getRecords($args)
    {
        $query = Package::query();

        if(isset($args['active_012']))  $query->where('active_012', $args['active_012']);
        if(isset($args['orderBy']))     $query->orderBy($args['orderBy']['column'], $args['orderBy']['order']);

        return $query->get();
    }
/*
    public static function ()
    {
        $modules = Package::get();
        $arrayAux = array();
        foreach ($modules as $module)
        {
            $arrayAux[$module->id_012] = $module;
        }
        return $arrayAux;
    }
*/
}