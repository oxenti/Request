# OxenTI Request API plugin for CakePHP 3

This plugin contains a package with API methods for managing Addresses on a CakePHP 3 application.

## Requirements

* CakePHP 3.0+

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```sh
composer require oxenti/request
```

## Configuration

In your app's `config/bootstrap.php` add:

```php
// In config/bootstrap.php
Plugin::load('Request');
```

or using cake's console:

```sh
./bin/cake plugin load Request
```
In your app's initial folder execute plugin's migrations:

./bin/cake migrations migrate -p Request

### Configuration files
Move the 'request.php' config file from the plugin's config folder to your app's config folder.

On your app's 'bootstrap.php' add the address configuration file:
```php
    ...
    try {
	    Configure::config('default', new PhpConfig());
	    Configure::load('app', 'default', false);
	} catch (\Exception $e) {
	    die($e->getMessage() . "\n");
	}

	Configure::load('request', 'default');
    ...
```

## Using extrenal Associations
If you want to associate the Request table with other tables on your application, use the request.php configuration file setting the 'relations' entry to your needs.

