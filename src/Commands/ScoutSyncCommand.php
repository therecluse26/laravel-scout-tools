<?php

declare(strict_types=1);

namespace TheRecluse26\ScoutTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use TheRecluse26\ScoutTools\ScoutToolsService;

class ScoutSyncCommand extends Command
{
	public $signature = 'scout:sync {--F|flush}';

	public $description = 'Update all registered Scout indexes';

	protected ScoutToolsService $service;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(ScoutToolsService $service)
	{
		parent::__construct();
		$this->service = $service;
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$models = $this->service->getSearchableModels(true);

		if ($this->option('flush')) {
			foreach ($models as $scoutModel) {
				$model = $scoutModel->getModel();
				$this->info("Flushing Scout indexes for $model...");
				$model = addslashes($model);
				Artisan::call("scout:flush $model");
			}
		}

		foreach ($models as $scoutModel) {
			$model = $scoutModel->getModel();
			$this->info("Updating Scout indexes for $model...");
			$model = addslashes($model);
			Artisan::call("scout:import $model");
		}
		$this->info("Scout update complete!");
		return self::SUCCESS;
	}
}
