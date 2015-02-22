<?php
return function () {
    return array(
    'host' => 'localhost',
    'port' => 80,
    'servers' => array(
      'host1',
      'host2',
      'host3'
    ),
    'application' => array(
      'name' => 'configuration',
      'secret' => 's3cr3t'
    )
    );
};
