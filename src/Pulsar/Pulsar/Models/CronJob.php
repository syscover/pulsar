<?php

/**
 * @package	Pulsar
 * @author	Jose Carlos Rodríguez Palacín (http://www.syscover.com/)
 */
namespace Pulsar\Pulsar\Models;

use Illuminate\Database\Eloquent\Model as Eloquent,
    Illuminate\Support\Facades\Validator,
    Pulsar\Pulsar\Libraries\Miscellaneous;

class CronJob extends Eloquent
{
	protected $table        = '001_043_cron_job';
    protected $primaryKey   = 'id_043';
    public $timestamps      = true;
    protected $fillable     = array('id_043', 'nombre_043', 'modulo_043', 'key_043', 'cron_expresion_043', 'last_run_043', 'next_run_043', 'activa_043');
    public static $rules = array(
        'nombre'        =>  'required|between:2,100',
        'modulo'        =>  'not_in:null',
        'cronExpresion' =>  'required|between:9,255|CronExpresion',
        'key'           =>  'required|between:2,50'
    );

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function getCronJobsLimit($aColumns, $nResultados = null, $inicio = null, $orden = null, $tipoOrden = null, $sWhere=null, $sWhereColumns=null, $count=false)
    {
        $query = CronJob::join('001_012_modulo', '001_043_cron_job.modulo_043', '=', '001_012_modulo.id_012')->newQuery();

        $query = Miscellaneous::getQueryWhere($aColumns, $query, $sWhere, $sWhereColumns);

        if($count)
        {
            return $query->count();
        }
        else
        {
            if($nResultados != null)    $query->take($nResultados)->skip($inicio);
            if($orden != null)          $query->orderBy($orden, $tipoOrden);

            return $query->get();
        }
    }

    public static function getCronJobsToRun($date)
    {
        return CronJob::where('next_run_043', '<=', $date)->where('activa_043', 1)->get();
    }

    public static function deleteCronJobs($ids)
    {
        CronJob::whereIn('id_043',$ids)->delete();
    }
}

/* End of file CronJob.php */
/* Location: ./Pulsar/Pulsar/Models/CronJob.php */