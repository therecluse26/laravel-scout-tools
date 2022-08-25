<?php

namespace TheRecluse26\ScoutTools\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use TheRecluse26\ScoutTools\ScoutToolsService;

class ScoutModelSearch extends Component
{
	private Collection $models;

	private string $bootstrap = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css';

	private string $template = "<link href='%s' rel='stylesheet' crossorigin='anonymous'>
								<div class='container'>
									<select id='model'>%s</select>
									<input id='query'>
									<button id='search'>Search</button>
								</div>";

	public function __construct()
	{
		$service = new ScoutToolsService();
		$this->models = $service->getSearchableModels();
	}

	public function render(): string
	{
		return sprintf($this->template, $this->bootstrap, $this->models->map(function ($model) {
			return '<option value="' . $model->getModel() . '">' . $model->getModel() . '</option>';
		})->join(''));
	}

}
