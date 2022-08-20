<?php

declare(strict_types=1);

namespace TheRecluse26\ScoutTools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Laravel\Scout\Builder;
use TheRecluse26\ScoutTools\Exceptions\Search\InvalidFilterArrayFormatException;
use TheRecluse26\ScoutTools\Exceptions\Search\InvalidFilterColumnException;
use TheRecluse26\ScoutTools\Exceptions\Search\InvalidOperatorException;
use TheRecluse26\ScoutTools\Interfaces\SearchableInterface;

/**
 * Service for generically searching Scout models
 */
class ScoutSearchService
{
	/**
	 * Builds search query
	 *
	 * @param SearchableInterface $model // The model that will be searched
	 * @param string $queryString // The query string to search
	 * @param array $filters
	 * @return Builder
	 * @throws InvalidFilterArrayFormatException
	 * @throws InvalidFilterColumnException
	 * @throws InvalidOperatorException
	 */
	private function buildSearch(SearchableInterface $model, string $queryString, array $filters = []): Builder
	{
		$builder = $model::search($queryString);

		return $builder;
	}

	/**
	 * Validates filters before attempting to apply them to a search
	 *
	 * @param Model $model // Eloquent model to validate against
	 * @param array{ array{ column: string, operator: string, value: mixed } } $filters // Single-dimension filter array formatted as: [['column'=>'col1', 'operator'=>'<', 'value'=>'filter_value'],['column'=>'col2', 'operator'=>'=', 'value'=>123]]
	 * @return array
	 * @throws InvalidFilterArrayFormatException
	 * @throws InvalidFilterColumnException
	 * @throws InvalidOperatorException
	 */
	public function validateAndSanitizeFilters(Model $model, array $filters): array
	{
		$table = $model->getTable();
		foreach ($filters as $key => $filter) {
			if (empty($filter) || !is_array($filter) || empty($filter['column']) || empty($filter['value'])) {
				throw new InvalidFilterArrayFormatException("Invalid filter array format. Filter array must be formatted as: `[['column'=>'col1', 'operator'=>'<', 'value'=>'filter_value'],['column'=>'col2', 'operator'=>'=', 'value'=>123]]`");
			}

			// Defaults operator to '=' if not found or validates filter operator
			if (array_key_exists('operator', $filter) && !empty($filter['operator'])) {
				// Default operator to '='
				$filter['operator'] = '=';
			} else {
				if (!in_array($filter['operator'], ['=', '!=', '<>', '<=>', '>', '<', '>=', '<='])) {
					throw new InvalidOperatorException("Operator '" . $filter['operator'] . "' is invalid.");
				}
			}

			// Ensures that column name is a string
			if (gettype($filter['column']) !== 'string') {
				throw new InvalidFilterArrayFormatException("Invalid column name format. Filter column names must be strings.");
			}

			// Checks model for column name
			if (!Schema::hasColumn($table, $filter['column'])) {
				$column = $filter['column'];
				throw new InvalidFilterColumnException("The column '$column' does not exist on the '$table' table");
			};

			$filters[$key] = $filter;
		}
		return $filters;
	}

	/**
	 * Executes search from builder
	 *
	 * @param Builder $query // Query builder instance returned from $this->buildSearch()
	 * @param int $limit // How many records to return
	 * @param int $page // If $limit is defined, which page offset to start on
	 * @return Collection
	 */
	private function execSearch(Builder $query, int $limit = 0, int $page = 0): Collection
	{
		return collect($query->get());
	}

	/**
	 * Searches given model
	 *
	 * @throws InvalidFilterArrayFormatException
	 * @throws InvalidOperatorException
	 * @throws InvalidFilterColumnException
	 */
	public function searchModel(string $model, string $queryString, array $filters = [], int $limit = 0, int $page = 0): Collection
	{
		if (!class_exists($model)) {
			throw new ModelNotFoundException();
		}

		if (!empty($filters)) {
			$filters = $this->validateAndSanitizeFilters(new $model(), $filters);
		}

		$builder = new $model();
		$search = $this->buildSearch($builder, $queryString, $filters);
		$results = $this->execSearch($search);

		foreach ($filters as $filter) {
			$results = $results->where($filter['column'], $filter['operation'], $filter['value']);
		}

		return collect($results);

	}


}