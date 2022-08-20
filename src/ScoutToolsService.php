<?php

declare(strict_types=1);

namespace TheRecluse26\ScoutTools;

use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use TheRecluse26\ScoutTools\Models\ScoutModel;

class ScoutToolsService
{
	/**
	 * Laravel Eloquent model class namespace
	 */
	const MODEL_NAMESPACE = Model::class;

	/**
	 * Laravel Scout Searchable class namespace
	 */
	const SEARCHABLE_NAMESPACE = Searchable::class;

	/**
	 * Returns array of models that use the Laravel scout "Searchable" trait
	 *
	 * @param string ...$namespaces // List of namespaces to check (variadic, so pass like getSearchableModels('App', 'App\Models'))
	 * @return array
	 *
	 * @throws Exception
	 */
	private function findSearchableModelsInProject(string ...$namespaces): array
	{
		$models = [];

		$classes = [];
		foreach ($namespaces as $namespace) {
			$classes = array_merge($classes, ClassFinder::getClassesInNamespace($namespace));
		}

		// Check if model uses searchable trait
		foreach ($classes as $class) {
			if ($this->checkIfSearchableModel($class)) {
				$models[] = $class;
			}
		}

		return $models;
	}

	/**
	 * Checks if class is an Eloquent model and uses the Laravel Scout "Searchable" trait
	 *
	 * @param string $class
	 * @return bool
	 */
	private function checkIfSearchableModel(string $class): bool
	{
		// Ensures that class is an actual Eloquent model
		if (!Arr::has(class_parents($class), self::MODEL_NAMESPACE)) {
			return false;
		}
		// Ensures that class uses Searchable trait
		if (!Arr::has(class_uses($class), self::SEARCHABLE_NAMESPACE)) {
			return false;
		}

		return true;
	}

	/**
	 * @throws Exception
	 */
	public function refreshSearchableModels(): void
	{
		ScoutModel::truncate();
		$models = $this->findSearchableModelsInProject('App', 'App\Models');
		foreach ($models as $model) {
			try {
				$indexed_fields = $this->getSearchableArrayKeys($model);
			} catch (\Throwable $e) {
				$indexed_fields = ['Unknown, ' . $e->getMessage()];
			}

			ScoutModel::updateOrCreate([
				'model' => $model,
			], [
				'indexed_fields' => json_encode($indexed_fields),
			]);
		}
	}

	public function getSearchableArrayKeys(string $model_name): array
	{
		$model = new $model_name();
		if ($model->count() == 0) {
			throw new Exception("No data exists for model $model_name");
		}

		return array_keys($model->first()->toSearchableArray());
	}

	public function getSearchableModels(bool $refresh = false): Collection
	{
		if ($refresh || ($models = ScoutModel::all())->isEmpty()) {
			$this->refreshSearchableModels();
			$models = ScoutModel::all();
		}

		return $models;
	}

	public function getModelIndexDetails(ScoutModel $scout_model)
	{
		return $scout_model;
	}


}
