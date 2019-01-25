<?php

$config['host'] = 'localhost';
$config['port'] = 80;
$config['servers'][0] = 'host1';
$config['servers'][1] = 'host2';
$config['servers'][2] = 'host3';
$config['application']['name'] = 'configuration';
$config['application']['secret'] = 's3cr3t';

return $config;
