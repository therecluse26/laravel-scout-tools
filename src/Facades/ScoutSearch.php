<?php

declare(strict_types=1);

namespace TheRecluse26\ScoutTools\Facades;

use Illuminate\Support\Facades\Facade;
use TheRecluse26\ScoutTools\ScoutSearchService;

class ScoutSearch extends Facade
{
	protected static function getFacadeAccessor()
	{
		return ScoutSearchService::class;
	}
}
