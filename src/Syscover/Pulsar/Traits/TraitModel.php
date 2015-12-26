<?php namespace Syscover\Pulsar\Traits;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Syscover\Pulsar\Libraries\Miscellaneous;

/**
 * Class TraitModel
 * @package Syscover\Pulsar\Traits
 */

trait TraitModel {

    /**
     * overwritte construct to set params to model
     *
     */
    function __construct(array $attributes = [])
    {
        // set maps to model
        $fields = $this->fillable;

        // set maps if not exist
        if(!isset($this->maps) || !is_array($this->maps))
        {
            throw new InvalidArgumentException('The array maps is not instantiated, you must instantiate in model ' . get_class($this));
        }

        foreach($fields as $field)
            $this->maps[str_replace('_' . $this->suffix, '', $field)] = $field;

        // set maps from relations
        if(isset($this->relationMaps))
        {
            foreach($this->relationMaps as $key => $relationClass)
            {
                $object     = new $relationClass;
                $maps       = $object->getMaps();

                foreach($maps as $keyMap => $map)
                    $this->maps[$key. '_' . $keyMap] = $map;
            }
        }

        // call parent constructor on Illuminate\Database\Eloquent\Model
        parent::__construct($attributes);
    }

    /**
     *
     * @param $query
     * @return mixed
     */
    public function scopeBuilder($query)
    {
        return $query;
    }

    /**
     * Function to get data record to list objects
     *
     * @access	public
     * @param   array     $parameters
     * @return	array|\Illuminate\Database\Eloquent\Model[]
     */
    public static function getIndexRecords($parameters)
    {
        $instance = new static;

        if(method_exists($instance, 'addToGetIndexRecords'))
        {
            $query = $instance->addToGetIndexRecords($parameters);
        }
        else
        {
            $query = $instance->query();
        }

        $query = Miscellaneous::getQueryWhere($query, isset($parameters['aColumns'])? $parameters['aColumns'] : null, isset($parameters['sWhere'])? $parameters['sWhere'] : null, isset($parameters['sWhereColumns'])? $parameters['sWhereColumns'] : null);

        // TODO: cambiar la forma de contar elementos, en comunik en contacts da fallo por el concact y en los multilenguajes da fallo al contar todos los registros de todos los idiomas
        if(isset($parameters['count']) &&  $parameters['count'])
        {
            // if we need count results
            if(method_exists($instance, 'countCustomIndexRecords'))
            {
                return $instance->countCustomIndexRecords($query, $parameters);
            }
            else
            {
                return $query->count();
            }
        }
        else
        {
            // if we need limit and order results
            if(isset($parameters['sLength']))     $query->take($parameters['sLength']);
            if(isset($parameters['sStart']))      $query->skip($parameters['sStart']);
            if(isset($parameters['sOrder']))      $query->orderBy($parameters['sOrder'], isset($parameters['sTypeOrder'])? $parameters['sTypeOrder'] : 'asc');


            if(method_exists($instance, 'getCustomReturnIndexRecords'))
            {
                // if we need a custom get()
                return $instance->getCustomReturnIndexRecords($query, $parameters);
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
     * @deprecated
     * @access	public
     * @param   string    $lang
     * @return	array|\Illuminate\Database\Query\Builder[]
     */
    public static function getTranslationsRecords($lang)
    {
        $instance = new static;
        return $instance::where('lang_' . $instance->suffix, $lang)->get();
    }

    /**
     * @deprecated
     * @access	public
     * @param   array     $parameters   [id, lang]
     * @return	\Illuminate\Database\Eloquent\Model
     */
    public static function getTranslationRecord($parameters)
    {
        $instance = new static;
        return $instance::builder()->where($instance->getKeyName(), $parameters['id'])->where('lang_' . $instance->suffix, $parameters['lang'])->first();
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

        $instance::where($instance->getKeyName(), $parameters['id'])->where('lang_' . $instance->suffix, $parameters['lang'])->delete();

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
                $jsonObject             = json_decode($object->{'data_lang_' . $instance->suffix});
                $jsonObject->langs[]    = $lang;
                $jsonString             = json_encode($jsonObject);

                // updates all objects with new language variables
                $instance::where($instance->getKeyName(), $id)->update([
                    'data_lang_' . $instance->suffix => $jsonString
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
            $jsonObject = json_decode($object->{'data_lang_' . $instance->suffix});

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
                'data_lang_' . $instance->suffix  => $jsonString
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
        $query = $instance->where($slugField, $slug);

        if($id != null)
        {
            $query->whereNotIn($instance->getKeyName(), [$id]);
        }

        $nObjects = $query->count();

        if($nObjects > 0)
        {
            $suffix = 0;
            while($nObjects > 0)
            {
                $suffix++;
                $slug       = $slug . '-' . $suffix;
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
    public static function getSuffix()
    {
        $instance = new static;

        return $instance->suffix;
    }
}