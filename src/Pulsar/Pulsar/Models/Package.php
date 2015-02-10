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

use Illuminate\Database\Eloquent\Model,
    Illuminate\Support\Facades\Validator,
    Pulsar\Pulsar\Libraries\Miscellaneous;

class Package extends Model {

	protected $table        = '001_012_package';
    protected $primaryKey   = 'id_012';
    public $timestamps      = true;
    protected $fillable     = array('id_012', 'name_012', 'active_012');
    public static $rules    = array(
        'nombre'    =>  'required|between:2,50'
    );
        
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}
        
    public static function getRecordsLimit($aColumns, $nResultados = null, $inicio = null, $orden = null, $tipoOrden = null, $sWhere=null, $sWhereColumns=null, $count=false)
    {
        $query = Package::query();

        $query = Miscellaneous::getQueryWhere($aColumns, $query, $sWhere, $sWhereColumns);

        if($count)
        {
            return $query->count();
        }
        else
        {
            if ($nResultados != null)   $query->take($nResultados)->skip($inicio);
            if ($orden != null)         $query->orderBy($orden, $tipoOrden);

            return $query->get();
        }
    }

    public static function getModulesForSession()
    {
        $modules = Package::get();
        $arrayAux = array();
        foreach ($modules as $module)
        {
            $arrayAux[$module->id_012] = $module;
        }
        return $arrayAux;
    }

    public static function deleteRecords($ids)
    {
        Package::whereIn('id_012', $ids)->delete();
    }
}