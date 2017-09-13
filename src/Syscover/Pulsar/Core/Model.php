<?php namespace Syscover\Pulsar\Core;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Syscover\Pulsar\Libraries\Miscellaneous;

/**
 * Class Model
 * @package Syscover\Pulsar\Core
 */

abstract class Model extends BaseModel
{
    protected $connection           = 'mysql2';
    protected $package;             // package name
    protected $table;               // string with table name
    protected $primaryKey;          // string with primary key
    protected $suffix;              // string with suffix number os table
    public $timestamps;             // boolean to set timestamps on table
    protected $fillable;            // array to set columns fillables
    public $tableTranslation;       // table translation string 
    public $columnTranslation;      // array that contain translation columns
    protected $maps;                // set map array sofa eloquent, this array if is empty, in model constructor is instantiated
    protected $relationMaps;        // array to set relations between columns and others models, to set maps array
    private static $rules;          // array to set rules to laravel validator

    /**
     * Overwrite construct to set params to model
     *
     * Model constructor.
     * @param array $attributes
     */
    function __construct(array $attributes = [])
    {
        // set translation name of table
        if(! empty($this->package) && empty($this->tableTranslation))
            $this->tableTranslation = $this->package . '::tables.' . $this->table;

        // set column translations
        if(empty($this->columnTranslation))
        {
            $this->columnTranslation = [];
            foreach ($this->fillable as $column)
            {
                if(trans_has($this->package . '::tables.' . $column))
                    $this->columnTranslation[$column] = $this->package . '::tables.' . $column;
            }
        }
        
        // set maps to model
        $fields = $this->fillable;

        // set maps if not exist
        if(! isset($this->maps) || ! is_array($this->maps))
            throw new InvalidArgumentException('The array maps is not instantiated, you must instantiate in model ' . get_class($this));

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
                    $this->maps[$key . '_' . $keyMap] = $map;

                // set translations columns from related models
                if(is_array($object->columnTranslation) && is_array($this->columnTranslation))
                    $this->columnTranslation = array_merge($this->columnTranslation, $object->columnTranslation);
                else
                    $this->columnTranslation = $object->columnTranslation;
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
     * @param   \Illuminate\Http\Request    $request
     * @param   array                       $parameters
     * @return	array|\Illuminate\Database\Eloquent\Model[]
     */
    public static function getIndexRecords($request, $parameters)
    {
        $instance = new static;

        if(method_exists($instance, 'addToGetIndexRecords'))
            $query = $instance->addToGetIndexRecords($request, $parameters);
        else
            if(method_exists($instance, 'scopeBuilder'))
                $query = $instance->builder();
            else
                $query = $instance->query();

        $query = Miscellaneous::getQueryWhere($request, $query, $parameters);

        if(isset($parameters['count']) &&  $parameters['count'])
        {
            // if we need count results
            if(method_exists($instance, 'customCountIndexRecords'))
                return $instance->customCountIndexRecords($query, $parameters);
            else
                return $query->count();
        }
        else
        {
            // if we need limit and order results
            if(isset($parameters['length']))
                $query->take($parameters['length']);

            if(isset($parameters['start']))
                $query->skip($parameters['start']);

            if(isset($parameters['order']) && is_array($parameters['order']))
                $query->orderBy($parameters['order']['column'], isset($parameters['order']['dir'])? $parameters['order']['dir'] : 'asc');


            if(method_exists($instance, 'getCustomReturnIndexRecords'))
            {
                // if we need a custom get()
                return $instance->getCustomReturnIndexRecords($query, $parameters);
            }
            else
            {
                // if has diplayColumns variable
                if(isset($parameters['displayColumns']) && is_array($parameters['displayColumns']) && count($parameters['displayColumns']) > 0)
                    return $query->get($parameters['displayColumns']);
                else
                    return $query->get();
            }
        }
    }

    /**
     * @access	public
     * @param   array     $parameters
     * @return	int
     */
    public static function countRecords($request, $parameters)
    {
        $instance = new static;

        if(method_exists($instance, 'customCount'))
            $query = $instance->customCount($request, $parameters);
        else
            $query = $instance->query();

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
        return $instance::where('lang_id_' . $instance->suffix, $lang)->get();
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
        return $instance::builder()->where($instance->getKeyName(), $parameters['id'])->where('lang_id_' . $instance->suffix, $parameters['lang'])->first();
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

        $instance::where($instance->getKeyName(), $parameters['id'])->where('lang_id_' . $instance->suffix, $parameters['lang'])->delete();

        if($deleteLangDataRecord)
            $instance::deleteLangDataRecord($parameters);
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