# Config

[![Join the chat at https://gitter.im/noodlehaus/config](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/noodlehaus/config?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/noodlehaus/config.svg?branch=develop)](https://travis-ci.org/noodlehaus/config)

`Config` is a file configuration loader that supports PHP, INI, XML, JSON, 
and YML files.

## Requirements

`Config` requires PHP 5.3+, and `symfony/yaml` for its YML support.

## Installation

The supported way of installing this is via `composer`.

```sh
$ composer require --prefer-dist noodlehaus/config
```

## How It Works

`Config` is designed to be very simple and straightforward to use. All you can do with
it is load, get, and set.

### Loading Files

The `Noodlehaus\Config` object can be created via the factory method `load`, or
by direct instantiation:

```php
// Load a single file
$conf = Config::load('config.json');
$conf = new Config('config.json');

// Load values from multiple files
$conf = new Config(['config.json', 'config.xml']);

// Load all supported files in a directory
$conf = new Config(__DIR__ . '/config');
```

Files are parsed and loaded depending on the file extension. Note that when 
loading multiple files, entries with **duplicate keys will take on the value
from the last loaded file**.

When loading a directory, the path is `glob`ed and files are loaded in by 
name alphabetically.

### Getting Values

Getting values can be done in two ways. One, by using the `get()` method:

```php
// Get value using key
$debug = $conf->get('debug');

// Get value using nested key
$secret = $conf->get('security.secret');

// Get a value with a fallback
$ttl = $conf->get('app.timeout', 3000);
```

The other method, is by using it like an array:

```php
// Get value using a simple key
$debug = $conf['debug'];

// Get value using a nested key
$secret = $conf['security.secret'];

// Get nested value like you would from a nested array
$secret = $conf['security']['secret'];
```

### Setting Values

Although `Config` supports setting values via `set()` or, via the
array syntax, **any changes made this way are NOT reflected back to the
source files**. It is by design that if you need to make changes to your
configuration files, you have to do it outside.

```php
$conf = Config::load('config.json');

// Sample value from our config file
assert($conf['secret'] == '123');

// Update config value to something else
$conf['secret'] = '456';

// Reload the file
$conf = Config::load('config.json');

// Same value as before
assert($conf['secret'] == '123');

// This will fail
assert($conf['secret'] == '456');
```

### Examples of Supported Configuration Files

Here's an example JSON file that we'll call `config.json`.

```json
{
    "app": {
        "host": "localhost",
        "port": 80,
        "base": "/my/app"
    },
    "security": {
        "secret": "s3cr3t-c0d3"
    },
    "debug": false
}
```

Here's the same config file in PHP format:

```php
<?php
return array(
    'app' => array(
        'host' => 'localhost',
        'port' => 80,
        'base' => '/my/app'
    ),
    'security' => array(
        'secret' => 's3cr3t-c0d3'
    ),
    'debug' => false
);
```

Or in a PHP file that returns a function that creates your config:

```php
return function () {
    // Normal callable function, returns array
    return array(
    'app' => array(
        'host' => 'localhost',
        'port' => 80,
        'base' => '/my/app'
    ),
    'security' => array(
        'secret' => 's3cr3t-c0d3'
    ),
    'debug' => false
    );
};
```

Or in an INI format:

```ini
debug = false

[app]
host = localhost
port = 80
base = /my/app

[security]
secret = s3cr3t-c0d3
```

Or in an XML format:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <app>
        <host>localhost</host>
        <port>80</port>
        <base>/my/app</base>
    </app>
    <security>
        <secret>s3cr3t-c0d3</secret>
    </security>
    <debug>false</debug>
</config>
```

Or in a YAML format:

```yaml
app:
    host: localhost
    port: 80
    base: /my/app
security:
    secret: s3cr3t-c0d3
debug: false
```

## License
MIT: <http://noodlehaus.mit-license.org>
