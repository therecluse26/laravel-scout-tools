<?php

return [
	'driver' => env('SCOUT_DRIVER'),
	'routes' => [
		'middleware' => ['auth:api'],
		'endpoints' => [
			'get-models' => ['path' => '/scout/models'],
			'refresh-models' => ['path' => '/scout/models/refresh'],
			'index-detail' => ['path' => '/scout/index/{scout_model}'],
		],
	],
];
