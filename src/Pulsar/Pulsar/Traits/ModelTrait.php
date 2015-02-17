<?php namespace Pulsar\Pulsar\Traits;

use Pulsar\Pulsar\Libraries\Miscellaneous;

trait ModelTrait {
    
    public static function getRecordsLimit($args)
    {
        $instance = new static;

        if(method_exists($instance, 'getCustomRecordsLimit'))
        {
            $query = $instance->getCustomRecordsLimit($args);
        }
        else{
            $query = $instance->query();
        }

        $query = Miscellaneous::getQueryWhere($query, $args['aColumns'], $args['sWhere'], isset($args['sWhereColumns'])? $args['sWhereColumns'] : null);

        if(isset($args['count']) &&  $args['count'])
        {
            return $query->count();
        }
        else
        {
            if(isset($args['nRecords']))    $query->take($args['nRecords']);
            if(isset($args['offset']))      $query->skip($args['offset']);
            if(isset($args['sorting']))     $query->orderBy($args['sorting'], isset($args['typeSorting'])? $args['typeSorting'] : 'asc');

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
    public static function getRecordsById($ids)
    {
        $instance = new static;
        return $instance->whereIn($instance->getKeyName(), $ids)->get();

    }

}