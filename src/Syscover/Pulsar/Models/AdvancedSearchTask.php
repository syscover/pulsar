<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class AdvancedSearchTask
 *
 * Model with properties
 * <br><b>[id, date, user_id, model, parameters, extension_file, filename, created]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class AdvancedSearchTask extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_022_advanced_search_task';
    protected $primaryKey   = 'id_022';
    protected $suffix       = '022';
    public $timestamps      = false;
    protected $fillable     = ['id_022', 'date_022', 'user_id_022', 'model_022', 'parameters_022', 'extension_file_022', 'filename_022', 'created_022'];
    protected $maps         = [];
    protected $relationMaps = [
        'user'   => \Syscover\Pulsar\Models\User::class
    ];
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query->join('001_010_user', '001_022_advanced_search_task.user_id_022', '=', '001_010_user.id_010');
    }

    public function getUser()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\User', 'user_id_022');
    }
}