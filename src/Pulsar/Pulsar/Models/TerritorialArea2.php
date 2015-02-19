<?php
/**
 *
 * Este modelo representa los datos de la tabla 001_004_area_territorial_2.
 *
 * @package	Pulsar
 * @author	Jose Carlos Rodríguez Palacín (http://www.syscover.com/)
 */
namespace Pulsar\Pulsar\Models;

use Illuminate\Database\Eloquent\Model as Eloquent,
    Illuminate\Support\Facades\Validator,
    Pulsar\Pulsar\Libraries\Miscellaneous;

class AreaTerritorial2 extends Eloquent
{
    protected $table            = '001_004_area_territorial_2';
    protected $primaryKey       = 'id_004';
    public $timestamps          = true;
    protected $fillable         = array('id_004','pais_004','area_territorial_1_004','nombre_004');
    private static $rules       = array(
        'nombre'                =>  'required|between:2,50',
        'area_territorial_1'    =>  'not_in:null'
    );

    public static function validate($data, $idRule=true)
    {
        if($idRule) static::$rules['id'] = 'required|between:1,10|unique:001_004_area_territorial_2,id_004';
        return Validator::make($data, static::$rules);
    }

    public function areasTerritoriales3()
    {
         return $this->hasMany('Pulsar\Pulsar\Models\AreaTerritorial3','area_territorial_2_005');
    }
     
    public static function getAreasTerritorialesLimit2($pais, $aColumns, $nResultados = null, $inicio = null, $orden = null, $tipoOrden = null, $sWhere=null, $sWhereColumns=null)
    {
        $query = AreaTerritorial2::join('001_003_area_territorial_1', '001_004_area_territorial_2.area_territorial_1_004', '=', '001_003_area_territorial_1.id_003')->newQuery();

        if($pais != null) $query->where('pais_004', '=', $pais);

        $query = Miscellaneous::getQueryWhere($aColumns, $query, $sWhere, $sWhereColumns);

        if($nResultados != null)    $query->take($nResultados)->skip($inicio);
        if($orden != null)          $query->orderBy($orden, $tipoOrden);

        return $query->get();
    }

    public static function getAllAreasTerritoriales2($area_terrirotial_1)
    {
        return AreaTerritorial2::where('area_territorial_1_004', '=', $area_terrirotial_1)->orderBy('nombre_004', 'asc')->get();
    }

    public static function deleteAreasTerritoriales2($ids)
    {
         AreaTerritorial2::whereIn('id_004',$ids)->delete();
    }
}

/* End of file areaterritorial2.php */
/* Location: ./Pulsar/Pulsar/Models/AreaTerritorial2.php */