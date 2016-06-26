<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Permission
 *
 * Model with properties
 * <br><b>[profile_id, resource_id, action]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Permission extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_009_permission';
    protected $primaryKey   = 'profile_id_009';
    protected $suffix       = '009';
    public $timestamps      = false;
    protected $fillable     = ['profile_id_009', 'resource_id_009', 'action_id_009'];
    protected $maps         = [];
    protected $relationMaps = [];
    private static $rules   = [
        'profile_id_009'    =>  'required',
        'resource_id_009'   =>  'required',
        'action_id_009'     =>  'required'
    ];
      
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}
        
    public static function deleteRecord($profile, $resource, $action)
    {
        Permission::where('profile_id_009', $profile)->where('resource_id_009', $resource)->where('action_id_009', $action)->delete();
    }

    public static function deleteRecordsProfile($profile)
    {
        Permission::where('profile_id_009', $profile)->delete();
    }

    public static function getRecord($profile)
    {
        return Permission::where('profile_id_009', $profile)->get();
    }
}