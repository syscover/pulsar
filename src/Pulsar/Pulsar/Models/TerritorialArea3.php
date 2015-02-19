<?php

/**
 *
 * Este modelo representa los datos de la tabla 001_005_area_territorial_3.
 *
 * @package	Pulsar
 * @author	Jose Carlos Rodríguez Palacín (http://www.syscover.com/)
 */
namespace Pulsar\Pulsar\Models;

use Illuminate\Database\Eloquent\Model as Eloquent,
    Illuminate\Support\Facades\Validator,
    Pulsar\Pulsar\Libraries\Miscellaneous;

class AreaTerritorial3 extends Eloquent
{
        protected $table        = '001_005_area_territorial_3';
        protected $primaryKey   = 'id_005';
        public $timestamps      = true;
        protected $fillable     = array('id_005','pais_005','area_territorial_1_005','area_territorial_2_005','nombre_005');
        public static $rules    = array(
            'nombre'                =>  'required|between:2,50',
            'area_territorial_1'    =>  'not_in:null',
            'area_territorial_2'    =>  'not_in:null'
        );
        
        public static function validate($data, $idRule=true) {
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
        
        public static function getAllAreasTerritoriales3($area_terrirotial_2){   
            return AreaTerritorial3::where('area_territorial_2_005', '=', $area_terrirotial_2)->orderBy('nombre_005', 'asc')->get();
        }
        
        public static function deleteAreasTerritoriales3($ids){
            AreaTerritorial3::whereIn('id_005',$ids)->delete();
        }
}

/* End of file areaterritorial3.php */
/* Location: ./Pulsar/Pulsar/Models/AreaTerritorial3.php */