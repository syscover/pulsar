<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Attachment
 *
 * Model with properties
 * <br><b>[id, lang_id, resource_id, object, family, library, library_file_name, sorting, name, file_name, mime, size, type, type_text, width, height, data_lang, data]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class Attachment extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_016_attachment';
    protected $primaryKey   = 'id_016';
    protected $suffix       = '016';
    public $timestamps      = false;
    public $incrementing    = false;
    protected $fillable     = ['id_016', 'lang_id_016', 'resource_id_016', 'object_016', 'family_016', 'library_016', 'library_file_name_016', 'sorting_016', 'name_016', 'file_name_016', 'mime_016', 'size_016', 'type_016', 'type_text_016', 'width_016', 'height_016', 'data_lang_016', 'data_016'];
    protected $maps         = [];
    protected $relationMaps = [
        'family'   => \Syscover\Pulsar\Models\AttachmentFamily::class
    ];
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query->leftJoin('001_015_attachment_family', '001_016_attachment.family_016', '=', '001_015_attachment_family.id_015');
    }

    public static function getTranslationsAttachmentsArticle($parameters)
    {
        return Attachment::builder()
            ->where('lang_id_016', $parameters['lang'])
            ->where('article_016', $parameters['article'])
            ->orderBy('sorting_016')
            ->get();
    }

    public static function getTranslationRecord($parameters)
    {
        return Attachment::builder()
            ->where('id_016', $parameters['id'])
            ->where('lang_id_016', $parameters['lang'])
            ->first();
    }

    /**
     * @deprecated
     * @param $args
     * @return mixed
     */
    public static function getRecords($args)
    {
        $query =  Attachment::builder();

        if(isset($args['lang_id_016']))     $query->where('lang_id_016', $args['lang_id_016']);
        if(isset($args['resource_id_016'])) $query->where('resource_id_016', $args['resource_id_016']);
        if(isset($args['object_016']))      $query->where('object_016', $args['object_016']);
        if(isset($args['family_016']))      $query->where('family_016', $args['family_016']);
        if(isset($args['orderBy']))         $query->orderBy($args['orderBy']['column'], $args['orderBy']['order']);
        if(isset($args['whereIn']))         $query->whereIn($args['whereIn']['column'], $args['whereIn']['ids']);

        return $query->get();
    }

    public static function deleteAttachment($args)
    {
        $query =  Attachment::builder();

        if(isset($args['lang_id_016']))     $query->where('lang_id_016', $args['lang_id_016']);
        if(isset($args['resource_id_016'])) $query->where('resource_id_016', $args['resource_id_016']);
        if(isset($args['object_016']))      $query->where('object_016', $args['object_016']);

        return $query->delete();
    }
}