<?php namespace Syscover\Pulsar\Models;

/**
 * @package	    Cms
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

class AttachmentFamily extends Model {

    use TraitModel;

	protected $table        = '001_015_attachment_family';
    protected $primaryKey   = 'id_015';
    public $timestamps      = false;
    protected $fillable     = ['id_015', 'resource_015', 'name_015', 'width_015', 'height_015', 'data_015'];
    private static $rules   = [
        'name'      => 'required|between:2,100',
        'resource'  => 'required',
        'width'     => 'numeric',
        'height'    => 'numeric'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public static function addToGetRecordsLimit($parameters)
    {
        $query =  AttachmentFamily::join('001_007_resource', '001_015_attachment_family.resource_015', '=', '001_007_resource.id_007')
            ->newQuery();

        return $query;
    }

    public static function getAttachmentFamilies($args)
    {
        $query =  AttachmentFamily::query();

        if(isset($args['resource_015'])) $query->where('resource_015', $args['resource_015']);

        return $query->get();
    }
}