<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

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
    protected $suffix       = '006';
    public $timestamps      = false;
    protected $fillable     = ['id_006', 'name_006'];
    protected $maps         = [];
    protected $relationMaps = [];
    private static $rules   = [
        'name'    =>  'required|between:2,50'
    ];
        
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query;
    }
}