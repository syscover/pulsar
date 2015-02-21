<?php namespace Syscover\Pulsar\Traits;

use Syscover\Pulsar\Libraries\Miscellaneous;

trait ModelTrait {

    /**
     * @access	public
     * @param   array     $parameters
     * @return	array|\Illuminate\Database\Eloquent\Model[]
     */
    public static function getRecordsLimit($parameters)
    {
        $instance = new static;

        if(method_exists($instance, 'getCustomRecordsLimit'))
        {
            $query = $instance->getCustomRecordsLimit($parameters);
        }
        else{
            $query = $instance->query();
        }

        $query = Miscellaneous::getQueryWhere($query, isset($parameters['aColumns'])? $parameters['aColumns'] : null, isset($parameters['sWhere'])? $parameters['sWhere'] : null, isset($parameters['sWhereColumns'])? $parameters['sWhereColumns'] : null);

        if(isset($parameters['count']) &&  $parameters['count'])
        {
            return $query->count();
        }
        else
        {
            if(isset($parameters['sLength']))     $query->take($parameters['sLength']);
            if(isset($parameters['sStart']))      $query->skip($parameters['sStart']);
            if(isset($parameters['sOrder']))      $query->orderBy($parameters['sOrder'], isset($parameters['sTypeOrder'])? $parameters['sTypeOrder'] : 'asc');

            return $query->get();
        }
    }

    /**
     * @access	public
     * @param   array     $parameters
     * @return	int
     */
    public static function countRecords($parameters)
    {
        $instance = new static;

        if(method_exists($instance, 'customCount'))
        {
            $query = $instance->customCount($parameters);
        }
        else{
            $query = $instance->query();
        }

        return $query->count();
    }

    /**
     * @access	public
     * @param   mixed     $id
     * @param   string    $lang
     * @return	\Illuminate\Database\Eloquent\Model
     */
    public static function getTranslationRecord($id, $lang)
    {
        $instance = new static;
        return $instance::where($instance->getKeyName(), $id)->where('lang_' . $instance->sufix, $lang)->first();
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
        $instance::where($instance->getKeyName(), $id)->where('lang_' . $instance->sufix, $lang)->delete();

        $instance::deleteLangDataRecord($id, $lang);
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

    public static function addLangDataRecord($id, $lang)
    {
        $instance   = new static;
        $object     = $instance::find($id);

        if($object != null)
        {
            $jsonObject             = json_decode($object->{'data_' . $instance->sufix});
            $jsonObject->langs[]    = $lang;
            $jsonString             = json_encode($jsonObject);

            $instance::where($instance->getKeyName(), $id)->update([
                'data_' . $instance->sufix  => $jsonString
            ]);
        }
        else
        {
            $jsonString = '{"langs":["' . $lang . '"]}';
        }

        return $jsonString;
    }

    public static function deleteLangDataRecord($id, $lang)
    {
        $instance   = new static;
        $object     = $instance::find($id);

        if($object != null)
        {
            $jsonObject = json_decode($object->{'data_' . $instance->sufix});

            //unset isn't correct, get error to reorder array
            $newArrayLang = [];
            foreach($jsonObject->langs as $keyLang)
            {
                if($keyLang != $lang)
                {
                    $newArrayLang[] = $keyLang;
                }
            }
            $jsonObject->langs = $newArrayLang;
            $jsonString = json_encode($jsonObject);

            $instance::where($instance->getKeyName(), $id)->update([
                'data_' . $instance->sufix  => $jsonString
            ]);
        }
    }
}