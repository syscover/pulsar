<?php namespace Pulsar\Pulsar\Traits;

use Pulsar\Pulsar\Libraries\Miscellaneous;

trait ModelTrait {

    public static function getRecordsLimit($aColumns, $nRecords = null, $offset = null, $sorting = null, $typeSorting = null, $sWhere = null, $sWhereColumns = null, $count = false)
    {
        $instance = new static;

        if(method_exists($instance, 'getCustomRecordsLimit'))
        {
            $query = $instance->getCustomRecordsLimit();
        }
        else{
            $query = $instance->query();
        }

        $query = Miscellaneous::getQueryWhere($aColumns, $query, $sWhere, $sWhereColumns);

        if($count)
        {
            return $query->count();
        }
        else
        {
            if($nRecords != null)    $query->take($nRecords)->skip($offset);
            if($sorting != null)          $query->orderBy($sorting, $typeSorting);

            return $query->get();
        }
    }

    public static function deleteRecords($ids)
    {
        $instance = new static;
        $instance->whereIn($instance->getKeyName(), $ids)->delete();
    }

    /**
     * @access	public
     * @param   array     $ids
     * @return	Collection
     */
    public function getRecordsById($ids)
    {
        $instance = new static;
        return $instance->whereIn($instance->getKeyName(), $ids)->get();

    }

}