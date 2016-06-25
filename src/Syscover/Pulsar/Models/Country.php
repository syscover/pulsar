<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Country
 *
 * Model with properties
 * <br><b>[id, lang, name, sorting, prefix, territorial_area_1, territorial_area_2, territorial_area_3, data_lang, data]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Country extends Model
{
    use Eloquence, Mappable;

    protected $table        = '001_002_country';
    protected $primaryKey   = 'id_002';
    public $incrementing    = false;
    protected $suffix       = '002';
    public $timestamps      = false;
    protected $fillable     = ['id_002', 'lang_id_002', 'name_002', 'sorting_002', 'prefix_002', 'territorial_area_1_002', 'territorial_area_2_002', 'territorial_area_3_002', 'data_lang_002', 'data_002'];
    protected $maps         = [];
    protected $relationMaps = [
        'lang' => Lang::class
    ];
    private static $rules   = [
        'id'                => 'required|alpha|size:2|unique:001_002_country,id_002',
        'name'              => 'required|between:2,100',
        'sorting'           => 'min:0|numeric',
        'prefix'            => 'between:1,5',
        'territorialArea1'  => 'between:0,50',
        'territorialArea2'  => 'between:0,50',
        'territorialArea3'  => 'between:0,50'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|alpha|size:2';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_001_lang', '001_002_country.lang_id_002', '=', '001_001_lang.id_001');
    }

    public function getLang()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Lang', 'lang_id_002');
    }

    public function getTerritorialAreas1()
    {
        return $this->hasMany('Syscover\Pulsar\Models\TerritorialArea1', 'country_id_003');
    }
    
    public function addToGetIndexRecords($request, $parameters)
    {
        $query =  $this->builder();
             
        if(isset($parameters['lang'])) $query->where('lang_id_002', $parameters['lang']);
        
        return $query;
    }

    public static function customCount($request, $parameters)
    {
        $query = Country::query();

        if(isset($parameters['lang'])) $query->where('lang_id_002', $parameters['lang']);

        return $query;
    }
}