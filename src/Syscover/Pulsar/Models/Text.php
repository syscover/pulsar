<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class Text
 *
 * Model with properties
 * <br><b>[id, name]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Text extends Model
{
    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_017_text';
    protected $primaryKey   = 'id_017';
    public $incrementing    = false;
    protected $suffix       = '017';
    public $timestamps      = false;
    protected $fillable     = ['id_017', 'lang_id_017', 'text_017'];
    protected $maps         = [];
    private static $rules   = [
        'lang'   => Lang::class
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|between:2,25';

        return Validator::make($data, static::$rules);
	}
}