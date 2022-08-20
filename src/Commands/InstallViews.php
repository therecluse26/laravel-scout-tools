<?php

declare(strict_types=1);

namespace TheRecluse26\ScoutTools\Commands;

use Illuminate\Console\GeneratorCommand;

class InstallViews extends GeneratorCommand
{
	public $signature = 'scout-tools:install-view {name} {--stub_path} {--output_path} {--output_namespace}';

	public $description = 'Installs scout-tools view';

	protected string $view_name;
	protected string $stub_path = '/../src/View/Components/Stubs/';
	protected string $output_path = 'views/scout-tools/';
	protected string $output_namespace = '\View\Components\Scout';

	protected function getStub()
	{
		return __DIR__ . '/..' . $this->stub_path . $this->view_name . '.php.stub';
	}

	protected function getDefaultNamespace($rootNamespace)
	{
		return $rootNamespace . $this->output_namespace;
	}

	public function handle()
	{
		$this->view_name = $this->argument('name');
		if ($stub_path = $this->option('stub_path')) {
			$this->stub_path = $stub_path;
		}
		if ($output_path = $this->option('output_path')) {
			$this->output_path = $output_path;
		}
		if ($output_namespace = $this->option('output_namespace')) {
			$this->output_namespace = $output_namespace;
		}

		parent::handle();

		$this->doOtherOperations();
	}

	protected function doOtherOperations()
	{
		// Get the fully qualified class name (FQN)
		$class = $this->qualifyClass($this->getNameInput());

		// get the destination path, based on the default namespace
		$path = $this->getPath($class);

		$content = file_get_contents($path);

		// Update the file content with additional data (regular expressions)
		file_put_contents($path, $content);
	}

}
