<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

/**
 * Class ReportTask
 *
 * Model with properties
 * <br><b>[id, date, user_id, email, subject, extension_file, filename, frequency, delivery_day, last_run, next_run, parameters, sql]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class ReportTask extends Model
{
    use Eloquence, Mappable;

	protected $table        = '001_023_report_task';
    protected $primaryKey   = 'id_023';
    protected $suffix       = '023';
    public $timestamps      = false;
    protected $fillable     = ['id_023', 'date_023', 'user_id_023', 'email_023', 'subject_023', 'extension_file_023', 'filename_023', 'frequency_023', 'delivery_day_023', 'last_run_023', 'next_run_023', 'parameters_023', 'sql_023'];
    protected $maps         = [
        'user'   => \Syscover\Pulsar\Models\User::class
    ];
    private static $rules   = [
        'email'             => 'required|between:2,255|email',
        'subject'           => 'required|between:2,255',
        'extensionFile'     => 'required',
        'filename'          => 'required',
        'frequency'         => 'required',
        'sql'               => 'required'
    ];

    public static function validate($data, $specialRules = [])
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query->join('001_010_user', '001_023_report_task.user_id_023', '=', '001_010_user.id_010');
    }

    public function getUser()
    {
        return $this->belongsTo('Syscover\Pulsar\Models\User', 'user_id_022');
    }
}