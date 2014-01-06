<?php
include __DIR__.'/../src/config.php';

use Noodlehaus\Config;

// json parsing exception
try {
  Config::load(__DIR__.'/error.json');
} catch (Exception $ex) {
  assert($ex->getMessage() == 'JSON parse error', "json file format exception test");
}

// ini parsing exception
try {
  Config::load(__DIR__.'/error.ini');
} catch (Exception $ex) {
  assert($ex->getMessage() == 'INI parse error', "ini file format exception test");
}

// unsupported file format test
try {
  Config::load('error.yaml');
} catch (Exception $ex) {
  assert($ex->getMessage() == 'Unsupported configuration format', "unknown format test");
}

// tests with no exceptions
foreach (array('config.ini', 'config.json') as $path) {

  $obj = Config::load(__DIR__."/{$path}");
  assert($obj instanceof Config);

  $val = $obj->get('host');
  assert($val === 'localhost', "test for simple get() key");

  $val = $obj->get('ttl', 128);
  assert($val === 128, "test simple get() key with default value");

  $val = $obj->get('application.name');
  assert($val === 'configuration', "test for nested get() key");

  $val = $obj->get('application.ttl', 128);
  assert($val === 128, "test for nested get() key with default value");

  $val = $obj->get('proxy');
  assert($val === null, "test for simple get() key that doesn't exist");

  $val = $obj->get('proxy.name');
  assert($val === null, "test for nested get() key that doesn't exist");

  $val = $obj->get('application');
  assert(is_array($val), "test for config subarray path for get()");
  assert($val['name'] === 'configuration', "test for config value from get() subarray");

  $val = $obj->get('servers');
  assert(is_array($val) && count($val) == 3, "test for array value for simple get() key");

  $obj->set('region', 'apac');
  $val = $obj->get('region');
  assert($val === 'apac', 'test for set() with simple key');

  $obj->set('location.country', 'Singapore');
  $val = $obj->get('location.country');
  assert($val === 'Singapore', 'test for set() with nested key');

  $obj->set('database', array(
    'host' => 'localhost',
    'name' => 'mydatabase'
  ));
  $val = $obj->get('database');
  assert(is_array($val), "test for mass key assignment in set()");
  $val = $obj->get('database.host');
  assert($val === 'localhost', "test nested path against mass key assignment with set()");
}

echo "If you didn't see any assertions fail, then all tests passed.\n";
?>
