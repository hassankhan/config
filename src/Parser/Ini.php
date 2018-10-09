<?php

namespace Noodlehaus\Parser;

use Noodlehaus\Exception\ParseException;

/**
 * INI parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Ini implements ParserInterface
{
    /**
     * {@inheritDoc}
     * Parses an INI file as an array
     *
     * @throws ParseException If there is an error parsing the INI file
     */
    public function parseFile($filename)
    {
        $data = @parse_ini_file($filename, true);
        return $this->parse($data, $filename);
    }

    /**
     * {@inheritDoc}
     * Parses an INI string as an array
     *
     * @throws ParseException If there is an error parsing the INI string
     */
    public function parseString($config)
    {
        $data = @parse_ini_string($config, true);
        return $this->parse($data);
    }

    /**
     * Completes parsing of INI data
     *
     * @param  array   $data
     * @param  strring $filename
     *
     * @throws ParseException If there is an error parsing the INI data
     */
    protected function parse($data = null, $filename = null)
    {
        if (!$data) {
            $error = error_get_last();

            // Parse functions may return NULL but set no error if the string contains no parsable data
            if (!is_array($error)) {
                $error["message"] = "No parsable content in data.";
            }

            $error["file"] = $filename;

            // if string contains no parsable data, no error is set, resulting in any previous error
            // persisting in error_get_last(). in php 7 this can be addressed with error_clear_last()
            if (function_exists("error_clear_last")) {
                error_clear_last();
            }

            throw new ParseException($error);
        }

        return $this->expandDottedKey($data);
    }

    /**
     * Expand array with dotted keys to multidimensional array
     *
     * @param array $data
     *
     * @return array
     */
    protected function expandDottedKey($data)
    {
        foreach ($data as $key => $value) {
            if (($found = strpos($key, '.')) !== false) {
                $newKey = substr($key, 0, $found);
                $remainder = substr($key, $found + 1);

                $expandedValue = $this->expandDottedKey([$remainder => $value]);
                if (isset($data[$newKey])) {
                    $data[$newKey] = array_merge_recursive($data[$newKey], $expandedValue);
                } else {
                    $data[$newKey] = $expandedValue;
                }
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedExtensions()
    {
        return ['ini'];
    }
}
