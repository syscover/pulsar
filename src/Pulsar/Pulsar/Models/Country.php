<?php namespace Pulsar\Pulsar\Models;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Pulsar\Pulsar\Traits\ModelTrait;

class Country extends Model
{
    use ModelTrait;

    protected $table        = '001_002_country';
    protected $primaryKey   = 'id_002';
    protected $langKey      = 'lang_002';
    public $timestamps      = false;
    protected $fillable     = ['id_002', 'lang_002', 'name_002', 'sorting_002', 'prefix_002', 'territorial_area_1_002', 'territorial_area_2_002', 'territorial_area_3_002', 'data_002'];
    public static $rules    = [
        'name'              => 'required|between:2,100',
        'sorting'           => 'required|min:0|numeric',
        'prefix'            => 'required|between:1,5',
        'territorialArea1'  => 'between:0,50',
        'territorialArea2'  => 'between:0,50',
        'territorialArea3'  => 'between:0,50'
    ];

    public static function validate($data, $idRule = true)
    {
        if ($idRule)    static::$rules['id'] = 'required|alpha|size:2|unique:001_002_pais,id_002';

        return Validator::make($data, static::$rules);
    }

    public function lang()
    {
        return $this->belongsTo('Pulsar\Pulsar\Models\Lang', 'lang_002');
    }

    public function territorialAreas1()
    {
        return $this->hasMany('Pulsar\Pulsar\Models\TerritorialArea1', 'country_003');
    }
    
    public static function getCustomRecordsLimit($args)
    {
        $query =  Country::join('001_001_lang', '001_002_country.lang_002', '=', '001_001_lang.id_001')->newQuery();
             
        if(isset($args['lang'])) $query->where('lang_002', $args['lang']);
        
        return $query;
    }








    //Función espejo que nos dara todos los idiomas para saber si hay que editar o crear, paises en diferentes idiomas
    public static function getContriesFromIdsXXX($ids)
    {
        if(is_array($ids) && count($ids) > 0)
        {
            return Country::join('001_001_lang', '001_002_country.lang_002', '=', '001_001_lang.id_001')->whereIn('id_002', $ids)->get();
        }
        return false;
    }

    public static function getCountriesByLangXXX($idioma)
    {
        return Country::join('001_001_lang', '001_002_country.lang_002', '=', '001_001_lang.id_001')
                ->where('idioma_002', '=', $idioma)
                ->orderBy('nombre_002')->get();
    }


}