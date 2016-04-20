<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Action
 *
 * Model with properties
 * <br><b>[id, name]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Action extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_008_action';
    protected $primaryKey   = 'id_008';
    public $incrementing    = false;
    protected $suffix       = '008';
    public $timestamps      = false;
    protected $fillable     = ['id_008', 'name_008'];
    protected $maps         = [];
    private static $rules   = [
        'id'    => 'required|between:2,25|unique:001_008_action,id_008',
        'name'  => 'required|between:2,50'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|between:2,25';

        return Validator::make($data, static::$rules);
	}
}