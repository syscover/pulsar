<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class TerritorialArea3
 *
 * Model with properties
 * <br><b>[id, country, territorial_area_1, territorial_area_2, name]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class TerritorialArea3 extends Model {

    use Eloquence, Mappable;

    protected $table        = '001_005_territorial_area_3';
    protected $primaryKey   = 'id_005';
    public $timestamps      = false;
    protected $fillable     = ['id_005', 'country_005', 'territorial_area_1_005', 'territorial_area_2_005', 'name_005'];
    protected $maps = [
        'id'                    => 'id_005',
        'country'               => 'country_005',
        'territorial_area_1'    => 'territorial_area_1_005',
        'territorial_area_2'    => 'territorial_area_2_005',
        'name'                  => 'name_005'
    ];
    private static $rules   = [
        'id'                => 'required|between:1,10|unique:001_005_territorial_area_3,id_005',
        'name'              => 'required|between:2,50',
        'territorialArea1'  => 'not_in:null',
        'territorialArea2'  => 'not_in:null'
    ];
        
    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|between:1,10';

        return Validator::make($data, static::$rules);
    }

    public static function addToGetRecordsLimit($parameters)
    {
        $query = TerritorialArea3::join('001_003_territorial_area_1', '001_005_territorial_area_3.territorial_area_1_005', '=', '001_003_territorial_area_1.id_003')
            ->join('001_004_territorial_area_2', '001_005_territorial_area_3.territorial_area_2_005', '=', '001_004_territorial_area_2.id_004')
            ->newQuery();

        if(isset($parameters['country'])) $query->where('country_003', $parameters['country']);

        return $query;
    }

    public static function customCount($parameters)
    {
        $query = TerritorialArea3::query();

        if(isset($parameters['country'])) $query->where('country_005', $parameters['country']);

        return $query;
    }

    public static function getTerritorialAreas3FromTerritorialArea2($terrirotialArea2)
    {
        return TerritorialArea3::where('area_territorial_2_005', $terrirotialArea2)->orderBy('name_005', 'asc')->get();
    }
}