<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class CronJob
 *
 * Model with properties
 * <br><b>[id, name, package, key, cron_expression, last_run, next_run, active]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class CronJob extends Model
{
    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_011_cron_job';
    protected $primaryKey   = 'id_011';
    protected $suffix       = '011';
    public $timestamps      = false;
    protected $fillable     = ['id_011', 'name_011', 'package_011', 'key_011', 'cron_expression_011', 'last_run_011', 'next_run_011', 'active_011'];
    protected $maps         = [];
    protected $relationMaps = [
        'package'   => \Syscover\Pulsar\Models\Package::class
    ];
    private static $rules   = [
        'name'              =>  'required|between:2,100',
        'package'           =>  'not_in:null',
        'cronExpression'    =>  'required|between:9,255|CronExpression',
        'key'               =>  'required|between:2,50'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_012_package', '001_011_cron_job.package_011', '=', '001_012_package.id_012');
    }

    public static function getCronJobsToRun($date)
    {
        return CronJob::builder()->where('next_run_011', '<=', $date)->where('active_011', true)->get();
    }
}