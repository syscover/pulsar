<?php namespace Syscover\Pulsar\Models;

/**
 * @package	    Syscover\Pulsar\Models
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Permission extends Model
{
	protected $table        = '001_009_permission';
    protected $primaryKey   = 'profile_009';
    public $timestamps      = false;
    protected $fillable     = ['profile_009', 'resource_009', 'action_009'];
    private static $rules   = [
        'profile_009'   =>  'required',
        'resource_009'  =>  'required',
        'action_009'    =>  'required'
    ];
      
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}
        
    public static function deleteRecord($profile, $resource, $action)
    {
        Permission::where('profile_009', $profile)->where('resource_009', $resource)->where('action_009', $action)->delete();
    }

    public static function deleteRecordsProfile($profile)
    {
        Permission::where('profile_009', $profile)->delete();
    }

    public static function getRecord($profile)
    {
        return Permission::where('profile_009', $profile)->get();
    }
}