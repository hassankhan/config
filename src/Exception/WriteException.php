<?php

namespace Noodlehaus\Exception;

use Noodlehaus\ErrorException;

class WriteException extends ErrorException
{
    public function __construct(array $error)
    {
        $message = isset($error['message']) ? $error['message'] : 'There was an error writing the file';
        $code = isset($error['code']) ? $error['code'] : 0;
        $severity = isset($error['type']) ? $error['type'] : 1;
        $filename = isset($error['file']) ? $error['file'] : __FILE__;
        $exception = isset($error['exception']) ? $error['exception'] : null;

        parent::__construct($message, $code, $severity, $filename, $exception);
    }
}
