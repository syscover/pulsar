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

class Action extends Model {

    use ModelTrait;

	protected $table        = '001_008_action';
    protected $primaryKey   = 'id_008';
    public $timestamps      = false;
    protected $fillable     = ['id_008', 'name_008'];
    public static $rules    = [
        'id'    => 'required|between:2,25|unique:001_008_action,id_008',
        'name'  => 'required|between:2,50'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|between:2,25';
        return Validator::make($data, static::$rules);
	}
}