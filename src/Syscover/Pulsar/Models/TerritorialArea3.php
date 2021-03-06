<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class TerritorialArea3
 *
 * Model with properties
 * <br><b>[id, country_id, territorial_area_1, territorial_area_2, name]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class TerritorialArea3 extends Model
{
    use Eloquence, Mappable;

    protected $table        = '001_005_territorial_area_3';
    protected $primaryKey   = 'id_005';
    public $incrementing    = false;
    protected $suffix       = '005';
    public $timestamps      = false;
    protected $fillable     = ['id_005', 'country_id_005', 'territorial_area_1_id_005', 'territorial_area_2_id_005', 'name_005'];
    protected $maps         = [];
    protected $relationMaps = [
        'terrirorial_area_1'   => TerritorialArea1::class,
        'terrirorial_area_2'   => TerritorialArea2::class
    ];
    private static $rules   = [
        'id'                => 'required|between:1,10|unique:mysql2.001_005_territorial_area_3,id_005',
        'name'              => 'required|between:2,50',
        'territorialArea1'  => 'not_in:null',
        'territorialArea2'  => 'not_in:null'
    ];
        
    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|between:1,10';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_003_territorial_area_1', '001_005_territorial_area_3.territorial_area_1_id_005', '=', '001_003_territorial_area_1.id_003')
            ->join('001_004_territorial_area_2', '001_005_territorial_area_3.territorial_area_2_id_005', '=', '001_004_territorial_area_2.id_004');
    }

    public function addToGetIndexRecords($request, $parameters)
    {
        $query = $this->builder();

        if(isset($parameters['country'])) $query->where('country_id_003', $parameters['country']);

        return $query;
    }

    public static function customCount($request, $parameters)
    {
        $query = TerritorialArea3::query();

        if(isset($parameters['country'])) $query->where('country_id_005', $parameters['country']);

        return $query;
    }

    public static function getTerritorialAreas3FromTerritorialArea2($terrirotialArea2)
    {
        return TerritorialArea3::where('area_territorial_2_005', $terrirotialArea2)->orderBy('name_005', 'asc')->get();
    }
}