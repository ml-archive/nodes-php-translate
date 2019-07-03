## Translate

A package to enable usage of translation services rather than local files.

[![Total downloads](https://img.shields.io/packagist/dt/nodes/translate.svg)](https://packagist.org/packages/nodes/translate)
[![Monthly downloads](https://img.shields.io/packagist/dm/nodes/translate.svg)](https://packagist.org/packages/nodes/translate)
[![Latest release](https://img.shields.io/packagist/v/nodes/translate.svg)](https://packagist.org/packages/nodes/translate)
[![Open issues](https://img.shields.io/github/issues/nodes-php/translate.svg)](https://github.com/nodes-php/translate/issues)
[![License](https://img.shields.io/packagist/l/nodes/translate.svg)](https://packagist.org/packages/nodes/translate)
[![Star repository on GitHub](https://img.shields.io/github/stars/nodes-php/translate.svg?style=social&label=Star)](https://github.com/nodes-php/translate/stargazers)
[![Watch repository on GitHub](https://img.shields.io/github/watchers/nodes-php/translate.svg?style=social&label=Watch)](https://github.com/nodes-php/translate/watchers)
[![Fork repository on GitHub](https://img.shields.io/github/forks/nodes-php/translate.svg?style=social&label=Fork)](https://github.com/nodes-php/translate/network)
[![StyleCI](https://styleci.io/repos/45786079/shield)](https://styleci.io/repos/45786079)

## 📝 Introduction

At [Nodes](http://nodesagency.com) we create a lot of stuff, which needs to be supported in multiple languages. The translation feature that comes out of the box
requires a developer to change it in a local file and commit and deploy that. But we wanted to use a service where other people - clients, project managers etc. - would be able to manage translations.

This package makes it easy to create translation providers and use them genericly in your [Laravel](http://laravel.com/docs/5.2) application.

Right it only comes with support for:

- [NStack](http://nstack.io)

But we very much welcome pull requests with providers to other services.

## 📦 Installation

To install this package you will need:

* Laravel 5.1+
* PHP 5.5.9+

You must then modify your `composer.json` file and run `composer update` to include the latest version of the package in your project.

```json
"require": {
    "nodes/translate": "^1.0"
}
```

Or you can run the composer require command from your terminal.

```bash
composer require nodes/translate:^1.0
```

## 🔧 Setup
> In Laravel 5.5 and above, service providers and aliases are [automatically registered](https://laravel.com/docs/5.5/packages#package-discovery). If you're using Laravel 5.5 or above, skip ahead directly to *Publish config files*.

Setup service provider in `config/app.php`

```php
Nodes\Translate\ServiceProvider::class
```

Setup alias in `config/app.php`

```php
'Translate' => Nodes\Translate\Support\Facades\Translate::class
```

Publish config files

```bash
php artisan vendor:publish --provider="Nodes\Translate\ServiceProvider"
```

If you want to overwrite any existing config files use the `--force` parameter

```bash
php artisan vendor:publish --provider="Nodes\Translate\ServiceProvider" --force
```
## ⚙ Usage

### Global methods

```php
translate($key, $replacements = [], $locale = null, $platform = null)

translate_app($app, $key, $replacements = [], $locale = null, $platform = null)

translate_or_fail($key, array $replacements = [], $locale = null, $platform = null)

translate_app_or_fail($application, $key, $replacements = [], $locale = null, $platform = null)

translate_with_fallback($key, $fallback, array $replacements = [], $locale = null, $platform = null)

translate_app_with_fallback($application, $key, $fallback, $replacements = [], $locale = null, $platform = null)
```

### Fallbacks

There are two ways to handle fallbacks, if key is either missing or NStack is down and cache is invalid

1) By default the translate() func will use laravel's trans('nstack.' . key) as fallback, that means you can download the nstack keys and reformat them to a php array in insert the file into `/ressources/lang/en/nstack.php`

2) Use the translate_with_fallback and decide what to use

## 🏆 Credits

This package is developed and maintained by the PHP team at [Nodes](http://nodesagency.com)

[![Follow Nodes PHP on Twitter](https://img.shields.io/twitter/follow/nodesphp.svg?style=social)](https://twitter.com/nodesphp) [![Tweet Nodes PHP](https://img.shields.io/twitter/url/http/nodesphp.svg?style=social)](https://twitter.com/nodesphp)

## 📄 License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
