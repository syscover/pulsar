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

class CustomFieldFamily extends Model
{
    use TraitModel;

	protected $table        = '001_025_field_family';
    protected $primaryKey   = 'id_025';
    public $timestamps      = false;
    protected $fillable     = ['id_025', 'name_025', 'resource_025', 'data_025'];
    private static $rules   = [
        'name'      => 'required|between:2,100',
        'resource'  => 'required',
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function addToGetRecordsLimit()
    {
        $query =  CustomFieldFamily::join('001_007_resource', '001_025_field_family.resource_025', '=', '001_007_resource.id_007')
            ->newQuery();

        return $query;
    }

    public static function getRecords($args)
    {
        $query = CustomFieldFamily::query();

        if(isset($args['resource_025'])) $query->where('resource_025', $args['resource_025']);

        return $query->get();
    }
}