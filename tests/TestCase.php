<?php

namespace TheRecluse26\ScoutTools\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use TheRecluse26\ScoutTools\ScoutToolsServiceProvider;

class TestCase extends Orchestra
{
	protected function setUp(): void
	{
		parent::setUp();

		Factory::guessFactoryNamesUsing(
			fn(string $modelName) => 'TheRecluse26\\ScoutTools\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
		);
	}

	protected function getPackageProviders($app)
	{
		return [
			ScoutToolsServiceProvider::class,
		];
	}

	public function getEnvironmentSetUp($app)
	{
		config()->set('database.default', 'testing');

//		$migration = include __DIR__ . '/../database/migrations/create_scout_models_table.php';
//		$migration->up();

	}
}
