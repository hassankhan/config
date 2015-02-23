<?php

namespace Noodlehaus\Exception;

use ErrorException;

class ParseException extends ErrorException
{
    public function __construct(array $error)
    {
        $message   = $error['message'];
        $code      = isset($error['code']) ? $error['code'] : 0;
        $severity  = isset($error['type']) ? $error['type'] : 1;
        $filename  = isset($error['file']) ? $error['file'] : __FILE__;
        $lineno    = isset($error['line']) ? $error['line'] : __LINE__;
        $exception = isset($error['exception']) ? $error['exception'] : null;

        parent::__construct($message, $code, $severity, $filename, $lineno, $exception);
    }
}
