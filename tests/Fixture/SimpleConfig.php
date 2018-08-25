<?php

namespace Noodlehaus\Test\Fixture;

use Noodlehaus\AbstractConfig;

class SimpleConfig extends AbstractConfig
{
    protected function getDefaults()
    {
        return [
            'host' => 'localhost',
            'port'    => 80,
            'servers' => [
                'host1',
                'host2',
                'host3'
            ],
            'application' => [
                'name'   => 'configuration',
                'secret' => 's3cr3t'
            ]
        ];
    }
}
