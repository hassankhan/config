# config

[![Build Status](https://travis-ci.org/noodlehaus/config.svg?branch=develop)](https://travis-ci.org/noodlehaus/config)

`config` is a file configuration loader that supports PHP,
INI, XML, JSON, and YAML files. Files are parsed and loaded
depending on the file extension.

Some examples of valid configuration files are [below](#examples)

## api

The `Config` object can be statically created or instantiated:

```php
//Using a file
$conf = Config::load('config.json');
$conf = new Config('config.json');

//Using an array of files
$conf = new Config(['config.json', 'config.xml']);

//Using a directory
$conf = new Config(__DIR__ . '/config');
```

When you call the constructor with an array of files keys will be
overwritten following the order of the files. It internally uses
[array_replace_recursive](http://php.net/manual/en/function.array-replace-recursive.php).

When you call the constructor with a directory, a list of ordered
by name files will be generated. Then it will use the same principle
of the constructor with an array of files to generate the keys.

**Please, note that each config file overwrites any values from the last.**

Use `get()` to retrieve values:
```php
// Get value using key
$debug  = $config->get('debug');

// Get value using nested key
$secret = $config->get('security.secret');

// Get a value with a fallback
$ttl    = $config->get('app.timeout', 3000);
```

Use `set()` to set values (doh!):
```php
$conf = Config::load('config.json');
$conf = new Config('config.json');
```

## examples

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

## license
MIT: <http://noodlehaus.mit-license.org>
