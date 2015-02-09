<?php

/**
 *
 * Este modelo representa los datos de la tabla idioma.
 *
 * @package	Pulsar
 * @author	Jose Carlos Rodríguez Palacín (http://www.syscover.com/)
 */

namespace Pulsar\Pulsar\Models;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Support\Facades\Validator,
    Pulsar\Pulsar\Libraries\Miscellaneous;

class Lang extends Model {

    protected $table        = '001_001_lang';
    protected $primaryKey   = 'id_001';
    public $timestamps      = false;
    protected $fillable     = array('id_001', 'name_001', 'image_001', 'sorting_001', 'base_001', 'active_001');
    public static $rules    = array(
        'nombre' => 'required|between:2,50',
        'orden' => 'required|min:0|numeric'
    );

    public static function validate($data, $imageRule = true, $idRule = true)
    {
        if ($imageRule)
            static::$rules['imagen'] = 'required|mimes:jpg,jpeg,gif,png|max:1000';
        if ($idRule)
            static::$rules['id'] = 'required|alpha|size:2|unique:001_001_lang,id_001';
        return Validator::make($data, static::$rules);
    }

    public static function resetIdiomaBase()
    {
        Lang::query()->update(array('base_001' => '0'));
    }

    public static function getIdiomaBase()
    {
        return Lang::where('base_001', '=', 1)->first();
    }

    public static function getIdiomasLimit($aColumns, $nResultados = null, $inicio = null, $orden = null, $tipoOrden = null, $sWhere=null, $sWhereColumns=null, $count=false)
    {
        $query = Lang::query();
        
        $query = Miscellaneous::getQueryWhere($aColumns, $query, $sWhere, $sWhereColumns);

        if($count)
        {
            return $query->count();
        }
        else
        {
            if($nResultados != null)    $query->take($nResultados)->skip($inicio);
            if($orden != null)          $query->orderBy($orden, $tipoOrden);

            return $query->get();
        }
    }

    public static function getIdiomasActivos()
    {
        return Lang::where('active_001', 1)->get();
    }
    
    public static function getIdsIdiomasActivos()
    {
        $ids = array();
        $idiomas = Lang::where('active_001', 1)->get();
        
        foreach ($idiomas as $idioma)
        {
            array_push($ids, $idioma->id_001);
        }
        return $ids;
    }

    public static function getIdiomasId($ids)
    {
        return Lang::whereIn('id_001', $ids)->get();
    }

    public static function deleteIdiomas($ids)
    {
        Lang::whereIn('id_001', $ids)->delete();
    }
}
/* End of file Idioma.php */
/* Location: ./Pulsar/Pulsar/Models/Lang.php */