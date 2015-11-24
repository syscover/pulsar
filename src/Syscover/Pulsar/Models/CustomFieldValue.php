<?php namespace Syscover\Pulsar\Models;

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
use Syscover\Pulsar\Traits\TraitModel;

class CustomFieldValue extends Model
{
    use TraitModel;

	protected $table        = '001_027_field_value';
    protected $primaryKey   = 'id_027';
    protected $sufix        = '027';
    public $timestamps      = false;
    protected $fillable     = ['id_027', 'lang_027', 'field_027', 'sorting_027', 'featured_027', 'value_027', 'data_lang_027', 'data_027'];
    private static $rules   = [
        'value' => 'required'
    ];

    public static function validate($data, $specialRules = [])
    {
        return Validator::make($data, static::$rules);
    }

    public function lang()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Lang', 'lang_027');
    }

    public static function addToGetRecordsLimit()
    {
        $query =  CustomFieldValue::join('001_001_lang', '001_027_field_value.lang_027', '=', '001_001_lang.id_001')
            ->newQuery();

        if(isset($parameters['lang'])) $query->where('lang_027', $parameters['lang']);

        return $query;
    }
}