<?php

namespace Noodlehaus\StringParser;

use Noodlehaus\Exception\ParseException;

/**
 * JSON string parser
 *
 * @package    Config
 * @author     Filip Š <projects@filips.si>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Json implements StringParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a JSON string as an array
     *
     * @throws ParseException If there is an error parsing the JSON string
     */
    public function parse($configuration)
    {
        $data = json_decode($configuration, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error_message  = 'Syntax error';
            if (function_exists('json_last_error_msg')) {
                $error_message = json_last_error_msg();
            }

            $error = [
                'message' => $error_message,
                'type'    => json_last_error(),
            ];
            throw new ParseException($error);
        }

        return $data;
    }
}
