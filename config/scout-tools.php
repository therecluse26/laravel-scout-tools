<?php

return [
	'driver' => env('SCOUT_DRIVER'),
	'routes' => [
		'middleware' => ['auth:api'],
		'endpoints' => [
			'get-models' => ['path' => '/api/scout/models'],
			'refresh-models' => ['path' => '/api/scout/models/refresh'],
			'index-detail' => ['path' => '/api/scout/index/{model}'],
		],
	],
];