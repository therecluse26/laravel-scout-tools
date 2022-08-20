<?php

namespace TheRecluse26\ScoutTools\Interfaces;

// Extends model interfaces to enforce model compliance

use Laravel\Scout\Builder;

interface SearchableInterface
{
	public static function bootSearchable();

	public function registerSearchableMacros();

	public function queueMakeSearchable($models);

	public function queueRemoveFromSearch($models);

	public function shouldBeSearchable();

	public function searchIndexShouldBeUpdated();

	public static function search($query = '', $callback = null);

	public static function makeAllSearchable($chunk = null);

	public function searchable();

	public static function removeAllFromSearch();

	public function unsearchable();

	public function wasSearchableBeforeUpdate();

	public function wasSearchableBeforeDelete();

	public function getScoutModelsByIds(Builder $builder, array $ids);

	public function queryScoutModelsByIds(Builder $builder, array $ids);

	public static function enableSearchSyncing();

	public static function disableSearchSyncing();

	public static function withoutSyncingToSearch($callback);

	public function searchableAs();

	public function toSearchableArray();

	public function searchableUsing();

	public function syncWithSearchUsing();

	public function syncWithSearchUsingQueue();

	public function pushSoftDeleteMetadata();

	public function scoutMetadata();

	public function withScoutMetadata($key, $value);

	public function getScoutKey();

	public function getScoutKeyName();
}