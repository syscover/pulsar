<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class Attachment
 *
 * Model with properties
 * <br><b>[id, lang, resource, object, family, library, library_file_name, sorting, name, file_name, mime, size, type, type_text, width, height, data_lang, data]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Attachment extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_016_attachment';
    protected $primaryKey   = 'id_016';
    protected $sufix        = '016';
    public $timestamps      = false;
    public $incrementing    = false;
    protected $fillable     = ['id_016', 'lang_016', 'resource_016', 'object_016', 'family_016', 'library_016', 'library_file_name_016', 'sorting_016', 'name_016', 'file_name_016', 'mime_016', 'size_016', 'type_016', 'type_text_016', 'width_016', 'height_016', 'data_lang_016', 'data_016'];
    protected $maps = [
        'id'                    => 'id_016',
        'lang'                  => 'lang_016',
        'resource'              => 'resource_016',
        'object'                => 'object_016',
        'family'                => 'family_016',
        'library'               => 'library_016',
        'library_file_name'     => 'library_file_name_016',
        'sorting'               => 'sorting_016',
        'name'                  => 'name_016',
        'file_name'             => 'file_name_016',
        'mime'                  => 'mime_016',
        'size'                  => 'size_016',
        'type'                  => 'type_016',
        'type_text'             => 'type_text_016',
        'width'                 => 'width_016',
        'height'                => 'height_016',
        'data_lang'             => 'data_lang_016',
        'data'                  => 'data_016',
    ];
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder()
    {
        return Attachment::leftJoin('001_015_attachment_family', '001_016_attachment.family_016', '=', '001_015_attachment_family.id_015')
            ->newQuery();
    }

    public static function getTranslationsAttachmentsArticle($parameters)
    {
        return Attachment::leftJoin('001_015_attachment_family', '001_016_attachment.family_016', '=', '001_015_attachment_family.id_015')
            ->where('lang_016', $parameters['lang'])
            ->where('article_016', $parameters['article'])
            ->orderBy('sorting_016')
            ->get();
    }

    public static function getTranslationRecord($parameters)
    {
        return Attachment::leftJoin('001_015_attachment_family', '001_016_attachment.family_016', '=', '001_015_attachment_family.id_015')
            ->where('id_016', $parameters['id'])
            ->where('lang_016', $parameters['lang'])
            ->first();
    }

    public static function getRecords($args)
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