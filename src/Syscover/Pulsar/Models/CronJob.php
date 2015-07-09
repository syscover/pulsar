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

class CronJob extends Model {

    use TraitModel;

	protected $table        = '001_011_cron_job';
    protected $primaryKey   = 'id_011';
    public $timestamps      = false;
    protected $fillable     = ['id_011', 'name_011', 'package_011', 'key_011', 'cron_expression_011', 'last_run_011', 'next_run_011', 'active_011'];
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
        return CronJob::join('001_012_package', '001_011_cron_job.package_011', '=', '001_012_package.id_012')->newQuery();
    }

    public static function getCronJobsToRun($date)
    {
        return CronJob::where('next_run_011', '<=', $date)->where('active_011', 1)->get();
    }
}