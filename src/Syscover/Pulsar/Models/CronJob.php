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
use Syscover\Pulsar\Traits\ModelTrait;

class CronJob extends Model {

    use ModelTrait;

	protected $table        = '001_043_cron_job';
    protected $primaryKey   = 'id_043';
    public $timestamps      = false;
    protected $fillable     = ['id_043', 'name_043', 'package_043', 'key_043', 'cron_expression_043', 'last_run_043', 'next_run_043', 'active_043'];
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

    public static function getCustomRecordsLimit()
    {
        return CronJob::join('001_012_package', '001_043_cron_job.package_043', '=', '001_012_package.id_012')->newQuery();
    }

    public static function getCronJobsToRun($date)
    {
        return CronJob::where('next_run_043', '<=', $date)->where('active_043', 1)->get();
    }
}