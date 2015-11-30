<?php namespace Syscover\Pulsar\Models;

/**
 * @package	    Syscover\Pulsar\Models
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscoverxx.com
 * @since		Version 2.0
 * @filesource
 *
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;

/**
 * Class Action
 * @package     Syscover\Pulsar\Models
 * @property    array   $fillable   ['id_008', 'name_008']
 *
 */
class Action extends Model {

    use TraitModel;

	protected $table        = '001_008_action';
    protected $primaryKey   = 'id_008';
    public $timestamps      = false;
    /** @var    array   esto es una descripción */
    protected $fillable     = ['id_008', 'name_008'];
    private static $rules   = [
        'id'    => 'required|between:2,25|unique:001_008_action,id_008',
        'name'  => 'required|between:2,50'
    ];

    /**
     * @param $data
     * @param array $specialRules
     * @return mixed
     */
    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['idRule']) && $specialRules['idRule'])   static::$rules['id'] = 'required|between:2,25';

        return Validator::make($data, static::$rules);
	}
}