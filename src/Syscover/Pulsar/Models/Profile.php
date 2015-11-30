<?php namespace Syscover\Pulsar\Models;

/**
 * @package	    Syscover\Pulsar\Models
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

class Profile extends Model
{
    use TraitModel;

	protected $table        = '001_006_profile';
    protected $primaryKey   = 'id_006';
    public $timestamps      = false;
    protected $fillable     = ['id_006', 'name_006'];
    private static $rules   = [
        'name'    =>  'required|between:2,50'
    ];
        
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}
}