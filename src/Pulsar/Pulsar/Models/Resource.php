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


class Resource extends Model
{
	protected $table        = '001_007_resource';
    protected $primaryKey   = 'id_007';
    public $timestamps      = false;
    protected $fillable     = ['id_007', 'name_007', 'package_007'];
    public static $rules    = [
        'module'    =>  'not_in:null',
        'name'      =>  'required|between:2,50'
    ];

    public static function validate($data, $idRule=true)
    {
        if($idRule) static::$rules['id'] = 'required|between:2,30|unique:001_007_resource,id_007';

        return Validator::make($data, static::$rules);
    }

    public static function getRecordsLimit($aColumns, $nResultados = null, $inicio = null, $orden = null, $tipoOrden = null, $sWhere=null, $sWhereColumns=null, $count=false)
    {
        $query = Resource::join('001_012_package', '001_007_resource.package_007', '=', '001_012_package.id_012')->newQuery();

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

    public static function deleteRecords($ids)
    {
        Resource::whereIn('id_007', $ids)->delete();
    }
}