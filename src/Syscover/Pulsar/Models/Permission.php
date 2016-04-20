<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Permission
 *
 * Model with properties
 * <br><b>[profile, resource, action]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Permission extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_009_permission';
    protected $primaryKey   = 'profile_009';
    protected $suffix       = '009';
    public $timestamps      = false;
    protected $fillable     = ['profile_009', 'resource_009', 'action_009'];
    protected $maps         = [];
    protected $relationMaps = [];
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