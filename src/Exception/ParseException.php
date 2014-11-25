<?php

namespace Noodlehaus\Exception;

class ParseException extends \ErrorException
{

    public function __construct(array $error)
    {
        $message   = $error['message'];
        $code      = isset($error['code']) ? $error['code'] : 0;
        $severity  = $error['type'];
        $filename  = $error['file'];
        $lineno    = $error['line'];
        $exception = isset($error['exception']) ? $error['exception'] : null;

        parent::__construct($message, $code, $severity, $filename, $lineno, $exception);
    }

}
