<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Lang
 *
 * Model with properties
 * <br><b>[id, name, image, sorting, base, active]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Lang extends Model
{
    use Eloquence, Mappable;

    protected $table        = '001_001_lang';
    protected $primaryKey   = 'id_001';
    public $incrementing    = false;
    protected $suffix       = '001';
    public $timestamps      = false;
    protected $fillable     = ['id_001', 'name_001', 'image_001', 'sorting_001', 'base_001', 'active_001'];
    protected $maps         = [];
    protected $relationMaps = [];
    private static $rules   = [
        'id'        => 'required|alpha|size:2|unique:mysql2.001_001_lang,id_001',
        'name'      => 'required|between:2,255',
        'sorting'   => 'min:0|numeric',
        'image'     => 'required'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule']) static::$rules['id'] = 'required|alpha|size:2';
        if(isset($specialRules['imageRule']) && $specialRules['imageRule']) static::$rules['image'] = 'required|mimes:jpg,jpeg,gif,png|max:1000';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query;
    }

    public static function getBaseLang()
    {
        return Lang::where('base_001', true)->first();
    }

    public static function resetBaseLang()
    {
        Lang::builder()->update(['base_001' => false]);
    }

    public static function getActivesLangs()
    {
        return Lang::where('active_001', true)->get();
    }
}