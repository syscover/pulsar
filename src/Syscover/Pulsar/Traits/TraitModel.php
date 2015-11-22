<?php namespace Syscover\Pulsar\Traits;

use Syscover\Pulsar\Libraries\Miscellaneous;

trait TraitModel {

    /**
     * @access	public
     * @param   array     $parameters
     * @return	array|\Illuminate\Database\Eloquent\Model[]
     */
    public static function getRecordsLimit($parameters)
    {
        $instance = new static;

        if(method_exists($instance, 'addToGetRecordsLimit'))
        {
            $query = $instance->addToGetRecordsLimit($parameters);
        }
        else
        {
            $query = $instance->query();
        }

        $query = Miscellaneous::getQueryWhere($query, isset($parameters['aColumns'])? $parameters['aColumns'] : null, isset($parameters['sWhere'])? $parameters['sWhere'] : null, isset($parameters['sWhereColumns'])? $parameters['sWhereColumns'] : null);

        if(isset($parameters['count']) &&  $parameters['count'])
        {
            // if we need count results
            return $query->count();
        }
        else
        {
            // if we need limit and order results
            if(isset($parameters['sLength']))     $query->take($parameters['sLength']);
            if(isset($parameters['sStart']))      $query->skip($parameters['sStart']);
            if(isset($parameters['sOrder']))      $query->orderBy($parameters['sOrder'], isset($parameters['sTypeOrder'])? $parameters['sTypeOrder'] : 'asc');


            if(method_exists($instance, 'getCustomReturnRecordsLimit'))
            {
                // if we need a custom get()
                return $instance->getCustomReturnRecordsLimit($query, $parameters);
            }
            else
            {
                return $query->get();
            }
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
        else
        {
            $query = $instance->query();
        }

        return $query->count();
    }

    /**
     * @access	public
     * @param   string    $lang
     * @return	array|\Illuminate\Database\Query\Builder[]
     */
    public static function getTranslationsRecords($lang)
    {
        $instance = new static;
        return $instance::where('lang_' . $instance->sufix, $lang)->get();
    }

    /**
     * @access	public
     * @param   array     $parameters   [id, lang]
     * @return	\Illuminate\Database\Eloquent\Model
     */
    public static function getTranslationRecord($parameters)
    {
        $instance = new static;
        return $instance::where($instance->getKeyName(), $parameters['id'])->where('lang_' . $instance->sufix, $parameters['lang'])->first();
    }

    /**
     * @access	public
     * @param   array       $parameters             [id, lang]
     * @param   boolean     $deleteLangDataRecord
     * @return	void
     */
    public static function deleteTranslationRecord($parameters, $deleteLangDataRecord = true)
    {
        $instance = new static;

        $instance::where($instance->getKeyName(), $parameters['id'])->where('lang_' . $instance->sufix, $parameters['lang'])->delete();

        if($deleteLangDataRecord)
        {
            $instance::deleteLangDataRecord($parameters);
        }
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

    /**
     * @access	public
     * @param   int $id
     * @param   string $lang
     * @param   string|null $jsonData
     * @return	string
     */
    public static function addLangDataRecord($lang, $id = null)
    {
        if($id === null)
        {
            $jsonString = json_encode(["langs" => [$lang]]);
        }
        else
        {
            $instance   = new static;
            $object     = $instance::find($id);

            if($object != null)
            {
                $jsonObject             = json_decode($object->{'data_lang_' . $instance->sufix});
                $jsonObject->langs[]    = $lang;
                $jsonString             = json_encode($jsonObject);

                // updates all objects with new language variables
                $instance::where($instance->getKeyName(), $id)->update([
                    'data_lang_' . $instance->sufix => $jsonString
                ]);
            }
            else
            {
                $jsonString = '{"langs":["' . $lang . '"]}';
            }

        }

        return $jsonString;
    }

    /**
     *  Function to delete lang record from json field
     *
     * @access  public
     * @param   array   $parameters     [id, lang]
     * @return  void
     */
    public static function deleteLangDataRecord($parameters)
    {
        $instance   = new static;
        $object     = $instance::find($parameters['id']);

        if($object != null)
        {
            $jsonObject = json_decode($object->{'data_lang_' . $instance->sufix});

            //unset isn't correct, get error to reorder array
            $newArrayLang = [];
            foreach($jsonObject->langs as $keyLang)
            {
                if($keyLang != $parameters['lang'])
                {
                    $newArrayLang[] = $keyLang;
                }
            }
            $jsonObject->langs = $newArrayLang;
            $jsonString = json_encode($jsonObject);

            $instance::where($instance->getKeyName(), $parameters['id'])->update([
                'data_lang_' . $instance->sufix  => $jsonString
            ]);
        }
    }

    /**
     *  Function to check if slug exists
     *
     * @access  public
     * @param   string          $slugField
     * @param   string          $slug
     * @param   integer|string  $id
     * @return  string          $slug
     */
    public static function checkSlug($slugField, $slug, $id = null)
    {
        $instance   = new static;

        $slug   = $slug;
        $query = $instance->where($slugField, $slug)->newQuery();

        if($id != null)
        {
            $query->whereNotIn($instance->getKeyName(), [$id]);
        }

        $nObjects = $query->count();

        if($nObjects > 0)
        {
            $sufix = 0;
            while($nObjects > 0)
            {
                $sufix++;
                $slug       = $slug . '-' . $sufix;
                $nObjects   = $instance->where($slugField, $slug)->count();
            }
        }

        return $slug;
    }

    /**
     * @access	public
     * @param   void
     * @return	string
     */
    public static function getSufix()
    {
        $instance = new static;

        return $instance->sufix;
    }
}