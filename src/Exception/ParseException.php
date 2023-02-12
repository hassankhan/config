<?php

namespace Noodlehaus\Exception;

use Noodlehaus\ErrorException;

class ParseException extends ErrorException
{
    public function __construct(array $error)
    {
        $message   = $error['message'] ?? 'There was an error parsing the file';
        $code      = $error['code'] ?? 0;
        $severity  = $error['type'] ?? 1;
        $filename  = $error['file'] ?? __FILE__;
        $lineno    = $error['line'] ?? __LINE__;
        $exception = $error['exception'] ?? null;

        parent::__construct($message, $code, $severity, $filename, $lineno, $exception);
    }
}
