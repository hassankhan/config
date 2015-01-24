<?php

namespace Noodlehaus\Exception;

class EmptyDirectoryException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
