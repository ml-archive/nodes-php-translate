## Translate

A package for translating text to [Laravel](http://laravel.com/docs).

[![Total downloads](https://img.shields.io/packagist/dt/nodes/translate.svg)](https://packagist.org/packages/nodes/translate)
[![Monthly downloads](https://img.shields.io/packagist/dm/nodes/translate.svg)](https://packagist.org/packages/nodes/translate)
[![Latest release](https://img.shields.io/packagist/v/nodes/translate.svg)](https://packagist.org/packages/nodes/translate)
[![Open issues](https://img.shields.io/github/issues/nodes-php/translate.svg)](https://github.com/nodes-php/translate/issues)
[![License](https://img.shields.io/packagist/l/nodes/translate.svg)](https://packagist.org/packages/nodes/translate)
[![Star repository on GitHub](https://img.shields.io/github/stars/nodes-php/translate.svg?style=social&label=Star)](https://github.com/nodes-php/translate/stargazers)
[![Watch repository on GitHub](https://img.shields.io/github/watchers/nodes-php/translate.svg?style=social&label=Watch)](https://github.com/nodes-php/translate/watchers)
[![Fork repository on GitHub](https://img.shields.io/github/forks/nodes-php/translate.svg?style=social&label=Fork)](https://github.com/nodes-php/translate/network)

## Introduction
One thing we at [Nodes](http://nodesagency.com) have been missing in [Laravel](http://laravel.com/docs) is a fast implemented translate providers and extending on it. This package have that.

## Providers
 - [NStack](http://nstack.io)

## 📦 Installation

To install this package you will need:

* Laravel 5.1+
* PHP 5.5.9+

You must then modify your `composer.json` file and run `composer update` to include the latest version of the package in your project.

```
"require": {
    "nodes/translate": "^1.0"
}
```

Or you can run the composer require command from your terminal.

```
composer require nodes/translate
```

## 🔧 Setup

Setup service providers in config/app.php

```
Nodes\Translate\ServiceProvider::class
```

Setup alias in config/app.php

```
'NodesTranslate' => Nodes\Translate\Support\Facades\Translate::class
```

Copy the config file from vendor/nodes/translate/config to config/nodes

## ⚙ Usage

###Global function

```php
translate($key, $replacements = [], $locale = null, $platform = null)
```

###Facade

```php
\NodesTranslate::get($key, $replacements = [], $locale = null, $platform = null) (alias)
```

## 🏆 Credits

This package is developed and maintained by the PHP team at [Nodes](http://nodesagency.com)

[![Follow Nodes PHP on Twitter](https://img.shields.io/twitter/follow/nodesphp.svg?style=social)](https://twitter.com/nodesphp) [![Tweet Nodes PHP](https://img.shields.io/twitter/url/http/nodesphp.svg?style=social)](https://twitter.com/nodesphp)

## 📄 License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
