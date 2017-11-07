<?php

namespace Noodlehaus\FileParser;

use Noodlehaus\Exception\ParseException;

/**
 * JSON file parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Json implements FileParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a JSON file as an array
     *
     * @throws ParseException If there is an error parsing the JSON file
     */
    public function parse($path)
    {
        $data = json_decode(file_get_contents($path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error_message  = 'Syntax error';
            if (function_exists('json_last_error_msg')) {
                $error_message = json_last_error_msg();
            }

            $error = array(
                'message' => $error_message,
                'type'    => json_last_error(),
                'file'    => $path,
            );
            throw new ParseException($error);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedExtensions()
    {
        return array('json');
    }
}
