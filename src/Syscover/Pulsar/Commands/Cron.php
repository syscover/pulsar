<?php namespace Syscover\Pulsar\Commands;

use Illuminate\Console\Command;
use Cron\CronExpression;
use Syscover\Pulsar\Models\CronJob;

class Cron extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cron {--v : Cron version}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Comand to run cron.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        if($this->option('v'))
        {
            $this->line("Cron Version 1.2");
            exit;
        }

        $now = date('U');

        $cronJobs = CronJob::getCronJobsToRun($now);

        foreach($cronJobs as $cronJob)
        {
            $callable = config('cron.' . $cronJob->key_011);

			call_user_func($callable); // call to static method

            $cron = CronExpression::factory($cronJob->cron_expression_011);

            CronJob::where('id_011', $cronJob->id_011)->update(array(
                'last_run_011'  => $now,
                'next_run_011'  => $cron->getNextRunDate()->getTimestamp()
            ));
        }
	}
}