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

use Illuminate\Database\Eloquent\Model as Eloquent,
    Illuminate\Support\Facades\Validator;

class Permission extends Eloquent
{
	protected $table        = '001_009_permission';
    protected $primaryKey   = 'perfil_009';
    public $timestamps      = false;
    protected $fillable     = array('profile_009', 'resource_009', 'action_009');
    public static $rules    = array(
        'perfil_009'    =>  'required',
        'recurso_009'   =>  'required',
        'accion_009'    =>  'required'
    );
      
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}
        
    public static function deleteRecord($profile, $resource, $action)
    {
        Permission::where('profile_009', $profile)->where('resource_009', $resource)->where('action_009', $action)->delete();
    }

    public static function getRecords($profile)
    {
        return Permission::where('profile_009', $profile)->get();
    }
}