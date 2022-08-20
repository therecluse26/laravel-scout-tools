<?php

declare(strict_types=1);

namespace TheRecluse26\ScoutTools\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * TheRecluse26\ScoutTools\Models\ScoutModel
 *
 * @property string $model
 * @property string|null $indexed_fields
 */
class ScoutModel extends Model
{
	protected $table = 'scout_models';

	protected $fillable = [
		'model',
		'indexed_fields',
	];

	public function getModel(): string
	{
		return $this->model;
	}

	public function instantiateModel(): Model
	{
		$model = $this->getModel();

		return new $model();
	}

	public function getFields()
	{
		return json_decode($this->indexed_fields);
	}
}
