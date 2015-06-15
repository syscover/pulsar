<?php namespace Syscover\Pulsar\Commands;

use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Syscover\Pulsar\Models\CronJob;

class Cron extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cron';

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
	public function fire()
	{
        $version = $this->option('v');
        if($version)
        {
            $this->line("Cron Version 1.1");
            exit(0);
        }

        $now = date('U');

        $cronJobs = CronJob::getCronJobsToRun($now);

        foreach($cronJobs as $cronJob)
        {
            $comand = Config::get('cron.' . $cronJob->key_011);

            $comand(); //run function

            $cron = CronExpression::factory($cronJob->cron_expression_011);

            CronJob::where('id_011', $cronJob->id_043)->update(array(
                'last_run_011'  => $now,
                'next_run_011'  => $cron->getNextRunDate()->getTimestamp()
            ));
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
        return [
            ['v', null, InputOption::VALUE_NONE, 'Cron Version', null],
        ];
	}
}