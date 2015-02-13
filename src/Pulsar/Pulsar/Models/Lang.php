<?php namespace Pulsar\Pulsar\Models;

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
use Pulsar\Pulsar\Traits\ModelTrait;

class Lang extends Model {

    use ModelTrait;

    protected $table        = '001_001_lang';
    protected $primaryKey   = 'id_001';
    public $timestamps      = false;
    protected $fillable     = ['id_001', 'name_001', 'image_001', 'sorting_001', 'base_001', 'active_001'];
    public static $rules    = [
        'name'      => 'required|between:2,50',
        'sorting'   => 'required|min:0|numeric'
    ];

    public static function validate($data, $imageRule = true, $idRule = true)
    {
        if ($imageRule)
            static::$rules['image'] = 'required|mimes:jpg,jpeg,gif,png|max:1000';
        if ($idRule)
            static::$rules['id'] = 'required|alpha|size:2|unique:001_001_lang,id_001';

        return Validator::make($data, static::$rules);
    }

    public static function getBaseLang()
    {
        return Lang::where('base_001', '=', 1)->first();
    }

    public static function resetBaseLang()
    {
        Lang::query()->update(array('base_001' => '0'));
    }




    public static function getIdiomasActivos()
    {
        return Lang::where('active_001', 1)->get();
    }
    
    public static function getIdsIdiomasActivos()
    {
        $ids = array();
        $idiomas = Lang::where('active_001', 1)->get();
        
        foreach ($idiomas as $idioma)
        {
            array_push($ids, $idioma->id_001);
        }
        return $ids;
    }
}