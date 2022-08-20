<?php

namespace TheRecluse26\ScoutTools\View\Components;

use Illuminate\View\Component;
use TheRecluse26\ScoutTools\Models\ScoutModel;

class ScoutModelTableRow extends Component
{
	private ScoutModel $model;

	private string $template = '<td>%s</td><td>%s</td>';

	public function __construct(ScoutModel $model)
	{
		$this->model = $model;
	}

	public function render(): string
	{
		return sprintf($this->template, $this->model->getModel(), collect($this->model->getFields())->join(', ', ''));
	}
}
