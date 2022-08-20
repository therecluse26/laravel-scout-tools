<?php

declare(strict_types=1);

namespace TheRecluse26\ScoutTools\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use TheRecluse26\ScoutTools\Models\ScoutModel;
use TheRecluse26\ScoutTools\ScoutSearchService;
use TheRecluse26\ScoutTools\ScoutToolsService;

class ScoutToolsController extends Controller
{
	public function refreshSearchableModels(ScoutToolsService $service): JsonResponse
	{
		try {
			return response()->json($service->getSearchableModels(true));
		} catch (\Throwable $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}

	public function getSearchableModels(ScoutToolsService $service): JsonResponse
	{
		try {
			return response()->json($service->getSearchableModels());
		} catch (\Throwable $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}

	public function indexDetail(ScoutToolsService $service, ScoutModel $scout_model): JsonResponse
	{
		try {
			return response()->json($service->getModelIndexDetails($scout_model));
		} catch (\Throwable $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}

	public function searchModel(ScoutSearchService $service, ScoutModel $scout_model, string $query): JsonResponse
	{
		try {
			return response()->json($service->searchModel($$scout_model::class, $query));
		} catch (\Throwable $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}
}
