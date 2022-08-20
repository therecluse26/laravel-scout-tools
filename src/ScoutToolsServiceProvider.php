<?php

declare(strict_types=1);

namespace TheRecluse26\ScoutTools;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TheRecluse26\ScoutTools\Commands\InstallViews;
use TheRecluse26\ScoutTools\Commands\ScoutSyncCommand;
use TheRecluse26\ScoutTools\View\Components\ScoutModelTable;
use TheRecluse26\ScoutTools\View\Components\ScoutModelTableRow;

class ScoutToolsServiceProvider extends PackageServiceProvider
{
	public function configurePackage(Package $package): void
	{
		/*
		 * This class is a Package Service Provider
		 *
		 * More info: https://github.com/spatie/laravel-package-tools
		 */
		$package
			->name('laravel-scout-tools')
			->hasConfigFile('scout-tools')
			->hasViews('scout-tools-views')
			->hasRoutes('scout-tools-routes')
			->hasMigration('create_scout_models_table')
			->hasCommand(ScoutSyncCommand::class);
	}

	public function boot()
	{
		$this->loadRoutesFrom(__DIR__ . '/../routes/scout-tools-routes.php');

		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'scout-tools-views');

		$this->registerComponents();

		if ($this->app->runningInConsole()) {

			// Export the migration
			if (!class_exists('CreateScoutModelsTable')) {
				// Publish migration
				$this->publishes([
					__DIR__ . '/../database/migrations/create_scout_models_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_scout_models_table.php'),
					// you can add any number of migrations here
				], 'scout-tools-migrations');
			}

			// Publish config file
			$this->publishes([
				__DIR__ . '/../config/scout-tools.php' => config_path('scout-tools.php'),
			], 'scout-tools-config');

			// Publish JS components
			$this->publishes([
				__DIR__ . '/../resources/components' => resource_path('assets/js/scout-tools'),
			], 'scout-tools-vue');

		}

		$this->commands([
			// Command to install Laravel Blade component
			InstallViews::class,
			// Command to register and synchronize Laravel Scout models
			ScoutSyncCommand::class,
		]);
	}

	public function registerComponents(): void
	{
		Blade::component('scout-model-table', ScoutModelTable::class);
		Blade::component('scout-model-table-row', ScoutModelTableRow::class);
	}
}
