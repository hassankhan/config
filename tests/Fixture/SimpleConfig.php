<?php

namespace Noodlehaus\Test\Fixture;

use Noodlehaus\AbstractConfig;

class SimpleConfig extends AbstractConfig
{
    public function __construct($options)
    {
        $this->data = $options;
    }
}
