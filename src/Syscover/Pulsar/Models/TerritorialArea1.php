<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class TerritorialArea1
 *
 * Model with properties
 * <br><b>[id, country_id, name]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class TerritorialArea1 extends Model
{
    use Eloquence, Mappable;

    protected $table        = '001_003_territorial_area_1';
    protected $primaryKey   = 'id_003';
    public $incrementing    = false;
    protected $suffix       = '003';
    public $timestamps      = false;
    protected $fillable     = ['id_003','country_id_003','name_003'];
    protected $maps         = [];
    protected $relationMaps = [
        'country'   => Country::class,
    ];
    private static $rules   = [
        'id'      => 'required|between:1,6|unique:mysql2.001_003_territorial_area_1,id_003',
        'name'    => 'required|between:2,50'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|between:1,6';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_002_country', '001_003_territorial_area_1.country_id_003', '=', '001_002_country.id_002');
    }

    public function getTerritorialAreas2()
    {
        return $this->hasMany('Syscover\Pulsar\Models\TerritorialArea2', 'territorial_area_1_id_004');
    }

    public function addToGetIndexRecords($request, $parameters)
    {
        $query = $this->builder();

        if(isset($parameters['country'])) $query->where('country_id_003', $parameters['country']);
        if(isset($parameters['lang']))
            $query->where('lang_id_002', $parameters['lang']);
        else
            $query->where('lang_id_002', base_lang2()->id_001);

        return $query;
    }

    public static function customCount($request, $parameters)
    {
        $query = TerritorialArea1::builder();

        if(isset($parameters['country'])) $query->where('country_id_003', $parameters['country']);
        if(isset($parameters['lang']))
            $query->where('lang_id_002', $parameters['lang']);
        else
            $query->where('lang_id_002', base_lang2()->id_001);

        return $query;
    }

    public static function getTerritorialAreas1FromCountry($country)
    {
        return TerritorialArea1::where('country_id_003', $country)
            ->orderBy('name_003', 'asc')
            ->get();
    }
}