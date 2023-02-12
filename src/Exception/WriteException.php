<?php

namespace Noodlehaus\Exception;

use Noodlehaus\ErrorException;

class WriteException extends ErrorException
{
    public function __construct(array $error)
    {
        $message = $error['message'] ?? 'There was an error writing the file';
        $code = $error['code'] ?? 0;
        $severity = $error['type'] ?? 1;
        $filename = $error['file'] ?? __FILE__;
        $exception = $error['exception'] ?? null;

        parent::__construct($message, $code, $severity, $filename, $exception);
    }
}
