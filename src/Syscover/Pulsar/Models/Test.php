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
use Sofa\Eloquence;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Mutable;

class Test extends Model {

    use TraitModel;

	protected $table        = 'user';
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    protected $fillable     = ['id', 'name', 'family'];
    protected $maps = [
        'id'        => 'userid',
        'name'      => 'username',
        'family'    => 'userfamily',
    ];
   // protected $hidden = ['id', 'name', 'family'];
    protected $appends = ['userid', 'username', 'userfamily'];
    private static $rules   = [

    ];

    public static function validate($data, $specialRules = [])
    {
        return Validator::make($data, static::$rules);
	}

    public static function getRecord(){
        return Test::join('family', 'user.family', '=', 'family.id')->get();
    }
}