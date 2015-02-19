<?php namespace Pulsar\Pulsar\Models;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Pulsar\Pulsar\Traits\ModelTrait;

class TerritorialArea1 extends Model {

    use ModelTrait;

    protected $table        = '001_003_territorial_area_1';
    protected $primaryKey   = 'id_003';
    public $timestamps      = false;
    protected $fillable     = ['id_003','country_003','name_003'];
    public static $rules    = [
        'id'      => 'required|between:1,6|unique:001_003_territorial_area_1,id_003',
        'name'    => 'required|between:2,50'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|between:1,6';

        return Validator::make($data, static::$rules);
    }

    public function TerritorialAreas2()
    {
         return $this->hasMany('Pulsar\Pulsar\Models\TerritorialArea2', 'territorial_area_1_004');
    }

    public static function getCustomRecordsLimit($parameters)
    {
        $query = TerritorialArea1::query();

        if(isset($parameters['country'])) $query->where('country_003', $parameters['country']);

        return $query;
    }

    public static function customCount($parameters)
    {
        $query = TerritorialArea1::query();

        if(isset($parameters['country'])) $query->where('country_003', $parameters['country']);

        return $query;
    }

    public static function getAllTerritorialAreas1($country)
    {
        return TerritorialArea1::where('country_003', $country)->orderBy('name_003', 'asc')->get();
    }
}