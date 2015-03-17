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
use Syscover\Pulsar\Traits\ModelTrait;

class Preference extends Model {

    use ModelTrait;

	protected $table        = '001_018_preference';
    protected $primaryKey   = 'id_018';
    public $timestamps      = false;
    protected $fillable     = ['id_018', 'value_018', 'package_018'];


    public static function destroyValue($id)
    {
        return Preference::destroy($id);
    }

    public static function getValue($id, $package, $default = "")
    {
        return Preference::firstOrCreate([
            'id_018'        => $id,
            'value_018'     => $default,
            'package_018'   => $package
        ]);
    }

    public static function setValue($id, $value, $package)
    {
        return Preference::where('id_018', $id)->updateOrCreate([
            'value_018'     => $value,
            'package_018'   => $package
        ]);
    }
}