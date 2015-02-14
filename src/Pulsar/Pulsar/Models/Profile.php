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

class Profile extends Model
{
    use ModelTrait;

	protected $table        = '001_006_profile';
    protected $primaryKey   = 'id_006';
    public $timestamps      = false;
    protected $fillable     = array('id_006', 'name_006');
    public static $rules    = array(
        'name'    =>  'required|between:2,50'
    );
        
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}
}