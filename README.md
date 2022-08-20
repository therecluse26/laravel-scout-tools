# Tools and enhancements for Laravel Scout

[![Latest Version on Packagist](https://img.shields.io/packagist/v/therecluse26/laravel-scout-tools.svg?style=flat-square)](https://packagist.org/packages/therecluse26/laravel-scout-tools)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/therecluse26/laravel-scout-tools/run-tests?label=tests)](https://github.com/therecluse26/laravel-scout-tools/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/therecluse26/laravel-scout-tools/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/therecluse26/laravel-scout-tools/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/therecluse26/laravel-scout-tools.svg?style=flat-square)](https://packagist.org/packages/therecluse26/laravel-scout-tools)

## Installation

You can install the package via composer:

```bash
composer require therecluse26/laravel-scout-tools
```

You can publish all custom resources with:

```bash
php artisan vendor:publish --provider="TheRecluse26\ScoutTools\ScoutToolsServiceProvider"
php artisan migrate
```

This will generate a migration file and a `config/scout-tools.php` file with the following contents:

(This config can be individually be generated using `php artisan vendor:publish --tag="scout-tools-config"`)

```php
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

```

This can be modified to specify custom route paths well as which middleware(s) to apply to the route group

### Views

Optionally, you can publish Vue templates to `/resources/assets/js/scout-tools` using

```bash
php artisan vendor:publish --tag="scout-tools-vue"
```

You can also publish Blade components using (these will be published to `app/View/Components/Scout`)

```bash
 php artisan scout-tools:install-view ScoutModelTable
 php artisan scout-tools:install-view ScoutModelTableRow
```

## Usage

This package provides some useful functionality missing from the base Laravel Scout package

### Artisan Commands

This package exposes a `php artisan scout:sync` command that can be used to automatically detect all project models with
the Scout `Searchable` trait and synchronize their data with the configured Scout service.

Passing a `--flush` or `-F` flag to this command will clear the Scout index data and re-sync it all from scratch.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/therecluse26/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [therecluse26](https://github.com/therecluse26)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
