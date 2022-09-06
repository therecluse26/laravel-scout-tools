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
	public static function findSearchableModelsInProject(string ...$namespaces): array
	{
		$models = [];

		$classes = [];
		foreach ($namespaces as $namespace) {
			$classes = array_merge($classes, ClassFinder::getClassesInNamespace($namespace));
		}

		// Check if model uses searchable trait
		foreach ($classes as $class) {
			if (self::checkIfSearchableModel($class)) {
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
	private static function checkIfSearchableModel(string $class): bool
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
	 * Refreshes ScoutModel data, searches project files for Laravel Scout `Searchable` trait
	 *
	 * @throws Exception
	 */
	public function refreshSearchableModels(): void
	{
		ScoutModel::truncate();
		$models = self::findSearchableModelsInProject('App', 'App\Models');
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

	/**
	 * Gets array keys that get passed to Scout
	 *
	 * @param string $model_name
	 * @return array
	 * @throws Exception
	 */
	public function getSearchableArrayKeys(string $model_name): array
	{
		$model = new $model_name();
		if ($model->count() == 0) {
			throw new Exception("No data exists for model $model_name");
		}

		return array_keys($model->first()->toSearchableArray());
	}

	/**
	 * Returns searchable model data as it's represented to Scout
	 *
	 * @param ScoutModel|string $model Searchable model
	 * @param int|null $paginate
	 * @return Collection
	 */
	public function getSearchableData(ScoutModel|string $model, int $paginate = null): Collection
	{
		if ($model instanceof ScoutModel) {
			$model = $model->getModel();
		}
		if ($paginate) {
			return $model::paginate($paginate)->map(function ($object) {
				return $object->toSearchableArray();
			});
		}
		return $model::all()->map(function ($object) {
			return $object->toSearchableArray();
		});
	}

	/**
	 * Returns list of searchable models
	 *
	 * @param bool $refresh Refreshes model list based on project file traits
	 * @return Collection
	 * @throws Exception
	 */
	public function getSearchableModels(bool $refresh = false): Collection
	{
		if ($refresh || ($models = ScoutModel::all())->isEmpty()) {
			$this->refreshSearchableModels();
			$models = ScoutModel::all();
		}

		return $models;
	}

	/**
	 * Returns basic details about ScoutModel record
	 *
	 * @param ScoutModel $scout_model
	 * @return ScoutModel
	 */
	public function getModelIndexDetails(ScoutModel $scout_model)
	{
		return $scout_model;
	}
	
}
