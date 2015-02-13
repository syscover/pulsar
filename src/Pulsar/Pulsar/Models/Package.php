<?php namespace Pulsar\Pulsar\Models;

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

class Package extends Model {

	protected $table        = '001_012_package';
    protected $primaryKey   = 'id_012';
    public $timestamps      = true;
    protected $fillable     = ['id_012', 'name_012', 'active_012'];
    public static $rules    = [
        'name'    =>  'required|between:2,50'
    ];
        
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public static function getModulesForSession()
    {
        $modules = Package::get();
        $arrayAux = array();
        foreach ($modules as $module)
        {
            $arrayAux[$module->id_012] = $module;
        }
        return $arrayAux;
    }
}