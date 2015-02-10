<?php namespace Pulsar\Pulsar\Traits;

use Pulsar\Pulsar\Libraries\Miscellaneous;

trait ModelTrait {

    public static function getRecordsLimit($aColumns, $nResultados = null, $inicio = null, $orden = null, $tipoOrden = null, $sWhere = null, $sWhereColumns = null, $count = false)
    {
        $instance = new static;
        $query = $instance->query();

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

    public static function deleteRecords($ids)
    {
        $instance = new static;
        $instance->whereIn($instance->getKeyName(), $ids)->delete();
    }


}