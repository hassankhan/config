<?php

namespace mhndev\config\Test\Fixture;

use mhndev\config\AbstractConfig;

class SimpleConfig extends AbstractConfig
{
    protected function getDefaults()
    {
        return array(
            'host' => 'localhost',
            'port'    => 80,
            'servers' => array(
                'host1',
                'host2',
                'host3'
            ),
            'application' => array(
                'name'   => 'configuration',
                'secret' => 's3cr3t'
            )
        );
    }
}
