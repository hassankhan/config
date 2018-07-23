<?php

namespace Noodlehaus\StringParser;

use Noodlehaus\Exception\ParseException;

/**
 * INI string parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Ini implements StringParserInterface
{
    /**
     * {@inheritDoc}
     * Parses an INI string as an array
     *
     * @throws ParseException If there is an error parsing the INI string
     */
    public function parse($configuration)
    {
        $data = @parse_ini_string($configuration, true);

        if (!$data) {
            $error = error_get_last();

            // parse_ini_string() may return NULL but set no error if the string contains no parsable data
            if (!is_array($error)) {
                $error["message"] = "No parsable content in string.";
            }

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
}
