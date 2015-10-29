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

class Attachment extends Model {

    use TraitModel;

	protected $table        = '001_016_attachment';
    protected $primaryKey   = 'id_016';
    protected $sufix        = '016';
    public $timestamps      = false;
    public $incrementing    = false;
    protected $fillable     = ['id_016', 'lang_016', 'resource_016', 'object_016', 'family_016', 'library_016', 'library_file_name_016', 'sorting_016', 'name_016', 'file_name_016', 'mime_016', 'size_016', 'type_016', 'type_text_016', 'width_016', 'height_016', 'data_lang_016', 'data_016'];
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public static function getTranslationsAttachmentsArticle($parameters)
    {
        return Attachment::leftJoin('001_015_attachment_family', '001_016_attachment.family_016', '=', '001_015_attachment_family.id_015')
            ->where('lang_016', $parameters['lang'])
            ->where('article_016', $parameters['article'])
            ->orderBy('sorting_016')
            ->get();
    }

    public static function getCustomTranslationRecord($parameters)
    {
        return Attachment::leftJoin('001_015_attachment_family', '001_016_attachment.family_016', '=', '001_015_attachment_family.id_015')
            ->where('id_016', $parameters['id'])
            ->where('lang_016', $parameters['lang'])
            ->first();
    }

    public static function getAttachments($args)
    {
        $query =  Attachment::leftJoin('001_015_attachment_family', '001_016_attachment.family_016', '=', '001_015_attachment_family.id_015')->newQuery();

        if(isset($args['lang_016']))        $query->where('lang_016', $args['lang_016']);
        if(isset($args['resource_016']))    $query->where('resource_016', $args['resource_016']);
        if(isset($args['object_016']))      $query->where('object_016', $args['object_016']);
        if(isset($args['family_016']))      $query->where('family_016', $args['family_016']);
        if(isset($args['orderBy']))         $query->orderBy($args['orderBy']['column'], $args['orderBy']['order']);
        if(isset($args['whereIn']))         $query->whereIn($args['whereIn']['column'], $args['whereIn']['ids']);

        return $query->get();
    }

    public static function deleteAttachment($args)
    {
        $query =  Attachment::query();

        if(isset($args['lang_016']))        $query->where('lang_016', $args['lang_016']);
        if(isset($args['resource_016']))    $query->where('resource_016', $args['resource_016']);
        if(isset($args['object_016']))      $query->where('object_016', $args['object_016']);

        return $query->delete();
    }
}