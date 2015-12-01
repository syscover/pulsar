<?php namespace Syscover\Pulsar\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;

/**
 * Class Profile
 *
 * Model with properties [id, name]
 *
 * @package     Syscover\Pulsar\Models
 */
class Profile extends Model
{
    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_006_profile';
    protected $primaryKey   = 'id_006';
    public $timestamps      = false;
    protected $fillable     = ['id_006', 'name_006'];
    protected $maps = [
        'id'                => 'id_006',
        'name'              => 'name_006',
    ];
    private static $rules   = [
        'name'    =>  'required|between:2,50'
    ];
        
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}
}