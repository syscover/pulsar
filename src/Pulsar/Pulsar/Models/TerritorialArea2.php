<?php namespace Pulsar\Pulsar\Models;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Pulsar\Pulsar\Traits\ModelTrait;

class TerritorialArea2 extends Model {

    use ModelTrait;

    protected $table            = '001_004_territorial_area_2';
    protected $primaryKey       = 'id_004';
    public $timestamps          = false;
    protected $fillable         = ['id_004', 'country_004', 'territorial_area_1_004', 'name_004'];
    private static $rules       = [
        'name'                  =>  'required|between:2,50',
        'territorialArea1'      =>  'not_in:null'
    ];

    public static function validate($data, $idRule=true)
    {
        if($idRule) static::$rules['id'] = 'required|between:1,10|unique:001_004_territorial_area_2,id_004';

        return Validator::make($data, static::$rules);
    }

    public function territorialAreas3()
    {
         return $this->hasMany('Pulsar\Pulsar\Models\TerritorialArea3','territorial_area_2_005');
    }
     
    public static function getCustomRecordsLimit($parameters)
    {
        return TerritorialArea2::join('001_003_territorial_area_1', '001_004_territorial_area_2.territorial_area_1_004', '=', '001_003_territorial_area_1.id_003')->newQuery();
    }

    public static function getAllAreasTerritoriales2($area_terrirotial_1)
    {
        return TerritorialArea2::where('area_territorial_1_004', '=', $area_terrirotial_1)->orderBy('nombre_004', 'asc')->get();
    }

    public static function deleteAreasTerritoriales2($ids)
    {
        TerritorialArea2::whereIn('id_004',$ids)->delete();
    }
}