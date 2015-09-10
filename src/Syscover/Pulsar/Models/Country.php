<?php namespace Syscover\Pulsar\Models;

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
use Syscover\Pulsar\Traits\TraitModel;

class Country extends Model
{
    use TraitModel;

    protected $table        = '001_002_country';
    protected $primaryKey   = 'id_002';
    protected $sufix        = '002';
    public $timestamps      = false;
    protected $fillable     = ['id_002', 'lang_002', 'name_002', 'sorting_002', 'prefix_002', 'territorial_area_1_002', 'territorial_area_2_002', 'territorial_area_3_002', 'data_lang_002', 'data_002'];
    private static $rules   = [
        'id'                => 'required|alpha|size:2|unique:001_002_country,id_002',
        'name'              => 'required|between:2,100',
        'sorting'           => 'min:0|numeric',
        'prefix'            => 'between:1,5',
        'territorialArea1'  => 'between:0,50',
        'territorialArea2'  => 'between:0,50',
        'territorialArea3'  => 'between:0,50'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|alpha|size:2';

        return Validator::make($data, static::$rules);
    }

    public function lang()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Lang', 'lang_002');
    }

    public function territorialAreas1()
    {
        return $this->hasMany('Syscover\Pulsar\Models\TerritorialArea1', 'country_003');
    }
    
    public static function getCustomRecordsLimit($parameters)
    {
        $query =  Country::join('001_001_lang', '001_002_country.lang_002', '=', '001_001_lang.id_001')->newQuery();
             
        if(isset($parameters['lang'])) $query->where('lang_002', $parameters['lang']);
        
        return $query;
    }

    public static function customCount($parameters)
    {
        $query = Country::query();

        if(isset($parameters['lang'])) $query->where('lang_002', $parameters['lang']);

        return $query;
    }
}