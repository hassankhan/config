# config

`config()` is a JSON or INI file configuration loader. Config files
are parsed and loaded depending on the file's extension name.

## example

Here's an example JSON file that we'll use as `config.json`.

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

Below's the code we'll use to work with this file.

```php
<?php
// load the config
$conf = config('config.json');

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
?>
```

## license
MIT: <http://noodlehaus.mit-license.org>
