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

class Library extends Model {

    use TraitModel;

	protected $table        = '001_014_library';
    protected $primaryKey   = 'id_014';
    protected $sufix        = '014';
    public $timestamps      = false;
    protected $fillable     = ['id_014', 'resource_014', 'url_014', 'file_name_014', 'mime_014', 'size_014', 'type_014', 'type_text_014', 'width_014', 'height_014', 'data_014'];
    private static $rules   = [
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function resource()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\Resource', 'resource_014');
    }
}