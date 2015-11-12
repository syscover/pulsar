<?php namespace Syscover\Pulsar\Models;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;

class Package extends Model {

    use TraitModel;

	protected $table        = '001_012_package';
    protected $primaryKey   = 'id_012';
    protected $sufix        = '012';
    public $timestamps      = false;
    protected $fillable     = ['id_012', 'name_012', 'folder_012', 'active_012', 'sorting_012'];
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