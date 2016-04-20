<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Preference
 *
 * Model with properties [id, value, package]
 *
 * @package     Syscover\Pulsar\Models
 */

class Preference extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_018_preference';
    protected $primaryKey   = 'id_018';
    public $incrementing    = false;
    protected $suffix       = '018';
    public $timestamps      = true;
    protected $fillable     = ['id_018', 'value_018', 'package_018'];
    protected $maps         = [];
    protected $relationMaps = [];
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function destroyValue($id)
    {
        return Preference::destroy($id);
    }

    public static function getValue($id, $package, $value = null)
    {
        $preference =  Preference::where([
            'id_018'        => $id,
            'package_018'   => $package
        ])->first();

        if($preference == null)
        {
            return Preference::setValue($id, $package, $value);
        }
        else
        {
            return $preference;
        }
    }

    public static function getValues($package)
    {
        return  Preference::where([
            'package_018'   => $package
        ])->get();
    }

    public static function setValue($id, $package, $value)
    {
        return Preference::updateOrCreate(['id_018' => $id],[
            'value_018'     => $value,
            'package_018'   => $package
        ]);
    }
}