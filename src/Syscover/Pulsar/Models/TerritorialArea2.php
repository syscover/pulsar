<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class TerritorialArea2
 *
 * Model with properties
 * <br><b>[id, country_id, territorial_area_1_id, name]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class TerritorialArea2 extends Model
{
    use Eloquence, Mappable;

    protected $table        = '001_004_territorial_area_2';
    protected $primaryKey   = 'id_004';
    public $incrementing    = false;
    protected $suffix       = '004';
    public $timestamps      = false;
    protected $fillable     = ['id_004', 'country_id_004', 'territorial_area_1_id_004', 'name_004'];
    protected $maps         = [];
    protected $relationMaps = [
        'terrirorial_area_1'   => TerritorialArea1::class
    ];
    private static $rules   = [
        'id'                => 'required|between:1,10|unique:001_004_territorial_area_2,id_004',
        'name'              => 'required|between:2,50',
        'territorialArea1'  => 'not_in:null'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|between:1,10';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_002_country', '001_004_territorial_area_2.country_id_004', '=', '001_002_country.id_002')
            ->join('001_003_territorial_area_1', '001_004_territorial_area_2.territorial_area_1_id_004', '=', '001_003_territorial_area_1.id_003');
    }

    public function getTerritorialAreas3()
    {
         return $this->hasMany('Syscover\Pulsar\Models\TerritorialArea3', 'territorial_area_2_id_005');
    }
     
    public function addToGetIndexRecords($request, $parameters)
    {
        $query = $this->builder();

        if(isset($parameters['country'])) $query->where('country_id_003', $parameters['country']);
        if(isset($parameters['lang']))
            $query->where('lang_id_002', $parameters['lang']);
        else
            $query->where('lang_id_002', base_lang()->id_001);

        return $query;
    }

    public static function customCount($request, $parameters)
    {
        $query = TerritorialArea2::builder();

        if(isset($parameters['country'])) $query->where('country_id_004', $parameters['country']);
        if(isset($parameters['lang']))
            $query->where('lang_id_002', $parameters['lang']);
        else
            $query->where('lang_id_002', base_lang()->id_001);

        return $query;
    }

    public static function getTerritorialAreas2FromTerritorialArea1($territorialArea1)
    {
        return TerritorialArea2::where('territorial_area_1_id_004', $territorialArea1)
            ->orderBy('name_004', 'asc')
            ->get();
    }
}