<?php

namespace Noodlehaus\FileParser;

use Noodlehaus\Exception\ParseException;

/**
 * INI file parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Ini implements FileParserInterface
{


    /**
     * {@inheritDoc}
     * Parses an INI file as an array
     *
     * @throws ParseException If there is an error parsing the INI file
     */
    public function parse($path)
    {
        $data = @parse_ini_file($path, true);

        if (!$data) {
            $error = error_get_last();

            // parse_ini_file() may return NULL but set no error if the file contains no parsable data
            if (!is_array($error)) {
                $error["message"] = "No parsable content in file.";
            }

            // if file contains no parsable data, no error is set, resulting in any previous error
            // persisting in error_get_last(). in php 7 this can be addressed with error_clear_last()
            if (function_exists("error_clear_last")) {
                error_clear_last();
            }

            throw new ParseException($error);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedExtensions()
    {
        return array('ini');
    }
}
