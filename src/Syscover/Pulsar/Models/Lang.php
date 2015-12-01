<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

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
    use TraitModel;
    use Eloquence, Mappable;

    protected $table        = '001_001_lang';
    protected $primaryKey   = 'id_001';
    protected $suffix       = '001';
    public $timestamps      = false;
    protected $fillable     = ['id_001', 'name_001', 'image_001', 'sorting_001', 'base_001', 'active_001'];
    protected $maps         = [];
    private static $rules   = [
        'id'        => 'required|alpha|size:2|unique:001_001_lang,id_001',
        'name'      => 'required|between:2,50',
        'sorting'   => 'min:0|numeric',
        'image'     => 'required'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule']) static::$rules['id'] = 'required|alpha|size:2';
        if(isset($specialRules['imageRule']) && $specialRules['imageRule']) static::$rules['image'] = 'required|mimes:jpg,jpeg,gif,png|max:1000';

        return Validator::make($data, static::$rules);
    }

    public static function getBaseLang()
    {
        return Lang::where('base_001', true)->first();
    }

    public static function resetBaseLang()
    {
        Lang::update(['base_001' => false]);
    }

    public static function getActivesLangs()
    {
        return Lang::where('active_001', true)->get();
    }
}