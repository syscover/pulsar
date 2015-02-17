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

        $query = Miscellaneous::getQueryWhere($query, isset($args['aColumns'])? $args['aColumns'] : null, isset($args['sWhere'])? $args['sWhere'] : null, isset($args['sWhereColumns'])? $args['sWhereColumns'] : null);

        if(isset($args['count']) &&  $args['count'])
        {
            return $query->count();
        }
        else
        {
            if(isset($args['sLength']))     $query->take($args['sLength']);
            if(isset($args['sStart']))      $query->skip($args['sStart']);
            if(isset($args['sOrder']))      $query->orderBy($args['sOrder'], isset($args['sTypeOrder'])? $args['sTypeOrder'] : 'asc');

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