<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

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
    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_008_action';
    protected $primaryKey   = 'id_008';
    public $timestamps      = false;
    protected $fillable     = ['id_008', 'name_008'];
    protected $maps = [
        'id'                => 'id_008',
        'name'              => 'name_008',
    ];
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