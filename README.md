# config

`config()` is a file configuration loader that supports PHP,
JSON and INI files. Files are parsed and loaded depending on
the file's extension name.

## api

The ``Config`` object can be statically created or instantianted:

```php
$conf = Config::load('config.json');
$conf = new Config('config.json');
```

Use ``get()`` to retrieve values:
```php
// Get value using key
$debug  = $config->get('debug');

// Get value using nested key
$secret = $config->get('security.secret');

// Get a value with a fallback
$ttl    = $config->get('app.timeout', 3000);
```

Use ``set()`` to set values (doh!):
```php
$conf = Config::load('config.json');
$conf = new Config('config.json');
```

```php

// import class
use Noodlehaus\Config;

// load the config
$conf = Config::load('config.json');

// this also works
$conf = new Config('config.json');

// get the "debug" flag
$debug = $conf->get('debug');

// get the "secret" value under "security"
$secret = $conf->get('security.secret');

// try to get a value with a default as fallback
$ttl = $conf->get('app.timeout', 3000);

// get an entire config subarray
$app = $conf->get('app');

// set some values, creating nesting if necessary
$conf->set('database.host', 'localhost');
$conf->set('database.name', 'mydatabase');

// you can also do subarray assignment
$conf->set('database', array(
  'host' => 'localhost',
  'name' => 'mydatabase'
));

// remove a value from the config (does not save to file)
$conf->set('app.timeout', null);
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

## license
MIT: <http://noodlehaus.mit-license.org>
