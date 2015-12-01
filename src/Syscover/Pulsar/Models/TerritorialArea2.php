<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class TerritorialArea2
 *
 * Model with properties
 * <br><b>[id, country, territorial_area_1, name]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class TerritorialArea2 extends Model
{
    use TraitModel;
    use Eloquence, Mappable;

    protected $table        = '001_004_territorial_area_2';
    protected $primaryKey   = 'id_004';
    protected $suffix       = '004';
    public $timestamps      = false;
    protected $fillable     = ['id_004', 'country_004', 'territorial_area_1_004', 'name_004'];
    protected $maps         = [];
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

    public function territorialAreas3()
    {
         return $this->hasMany('Syscover\Pulsar\Models\TerritorialArea3','territorial_area_2_005');
    }
     
    public static function addToGetRecordsLimit($parameters)
    {
        $query = TerritorialArea2::join('001_003_territorial_area_1', '001_004_territorial_area_2.territorial_area_1_004', '=', '001_003_territorial_area_1.id_003');

        if(isset($parameters['country'])) $query->where('country_003', $parameters['country']);

        return $query;
    }

    public static function customCount($parameters)
    {
        $query = TerritorialArea2::query();

        if(isset($parameters['country'])) $query->where('country_004', $parameters['country']);

        return $query;
    }

    public static function getTerritorialAreas2FromTerritorialArea1($terrirotialArea1)
    {
        return TerritorialArea2::where('territorial_area_1_004', $terrirotialArea1)->orderBy('name_004', 'asc')->get();
    }
}