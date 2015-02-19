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
use Pulsar\Pulsar\Libraries\Miscellaneous;

class TerritorialArea3 extends Model {

    use ModelTrait;

    protected $table        = '001_005_territorial_area_3';
    protected $primaryKey   = 'id_005';
    public $timestamps      = false;
    protected $fillable     = array('id_005', 'country_005', 'territorial_area_1_005', 'territorial_area_2_005', 'name_005');
    public static $rules    = array(
        'name'                =>  'required|between:2,50',
        'territorialArea1'    =>  'not_in:null',
        'territorialArea2'    =>  'not_in:null'
    );
        
    public static function validate($data, $idRule=true)
    {
        if($idRule)     static::$rules['id'] = 'required|between:1,10|unique:001_005_area_territorial_3,id_005';

        return Validator::make($data, static::$rules);
    }
        




    public static function getAreasTerritorialesLimit3($pais, $aColumns, $nResultados = null, $inicio = null, $orden = null, $tipoOrden = null, $sWhere=null, $sWhereColumns=null){
        $query = AreaTerritorial3::join('001_003_area_territorial_1', '001_005_area_territorial_3.area_territorial_1_005', '=', '001_003_area_territorial_1.id_003')->join('001_004_area_territorial_2', '001_005_area_territorial_3.area_territorial_2_005', '=', '001_004_area_territorial_2.id_004')->newQuery();

        if($pais != null) $query->where('pais_005', '=', $pais);

        $query = Miscellaneous::getQueryWhere($aColumns, $query, $sWhere, $sWhereColumns);

        if($nResultados != null)    $query->take($nResultados)->skip($inicio);
        if($orden != null)          $query->orderBy($orden, $tipoOrden);

        return $query->get();
    }

    public static function getAllAreasTerritoriales3($area_terrirotial_2)
    {
        return AreaTerritorial3::where('area_territorial_2_005', '=', $area_terrirotial_2)->orderBy('nombre_005', 'asc')->get();
    }

    public static function deleteAreasTerritoriales3($ids)
    {
        AreaTerritorial3::whereIn('id_005',$ids)->delete();
    }
}