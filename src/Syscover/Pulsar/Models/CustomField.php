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

class CustomField extends Model
{
    use TraitModel;

	protected $table        = '001_026_field';
    protected $primaryKey   = 'id_026';
    protected $sufix        = '026';
    public $timestamps      = false;
    protected $fillable     = ['id_026', 'name_026', 'family_026', 'data_lang_026', 'data_026'];
    private static $rules   = [
        'name'      => 'required|between:2,100',
        'family'    => 'required',
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function getCustomRecordsLimit()
    {
        $query =  CustomField::join('001_025_field_family', '001_026_field.family_026', '=', '001_025_field_family.id_025')
            ->newQuery();

        return $query;
    }

    public static function getCustomFieldFamilies($args)
    {
        $query =  CustomFieldFamily::query();

        if(isset($args['resource_025'])) $query->where('resource_025', $args['resource_025']);

        return $query->get();
    }
}