<?php namespace Pulsar\Pulsar\Traits;

use Pulsar\Pulsar\Libraries\Miscellaneous;

trait ModelTrait {

    /**
     * @access	public
     * @param   array     $args
     * @return	array|\Illuminate\Database\Query\Builder[]
     */
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

    /**
     * @access	public
     * @param   mixed     $id
     * @param   string    $lang
     * @return	array|\Illuminate\Database\Query\Builder[]
     */
    public static function getTranslationRecord($id, $lang)
    {
        $instance = new static;
        return $instance::where($instance->getKeyName(), $id)->where($instance->langKey, $lang)->first();
    }

    /**
     * @access	public
     * @param   mixed     $id
     * @param   string    $lang
     * @return	void
     */
    public static function deleteTranslationRecord($id, $lang)
    {
        $instance = new static;
        $instance::where($instance->getKeyName(), $id)->where($instance->langKey, $lang)->delete();
    }

    /**
     * @access	public
     * @param   array     $ids
     * @return	void
     */
    public static function deleteRecords($ids)
    {
        $instance = new static;
        $instance->whereIn($instance->getKeyName(), $ids)->delete();
    }

    /**
     * @access	public
     * @param   array     $ids
     * @return	array|\Illuminate\Database\Query\Builder[]
     */
    public static function getRecordsById($ids)
    {
        $instance = new static;
        return $instance->whereIn($instance->getKeyName(), $ids)->get();

    }

}