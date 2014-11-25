<?php

namespace Noodlehaus\Exception;

class UnsupportedFormatException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
