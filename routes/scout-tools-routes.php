<?php

declare(strict_types=1);

namespace TheRecluse26\ScoutTools;

use Illuminate\Support\Facades\Route;
use TheRecluse26\ScoutTools\Http\Controllers\ScoutToolsController;

Route::group(['middleware' => config('scout-tools.routes.middleware', ['auth:api'])], function () {
	Route::get(config('scout-tools.routes.endpoints.get-models.path', '/api/scout/models'), [ScoutToolsController::class, 'getSearchableModels'])->name('scout-get-models');
	Route::get(config('scout-tools.routes.endpoints.refresh-models.path', '/api/scout/models/refresh'), [ScoutToolsController::class, 'refreshSearchableModels'])->name('scout-refresh-models');
	Route::get(config('scout-tools.routes.endpoints.index-detail.path', '/api/scout/index/{scout_model}'), [ScoutToolsController::class, 'indexDetail'])->name('scout-index-detail');
});
