<?php

namespace Noodlehaus\FileParser;

use Exception;
use Noodlehaus\Exception\ParseException;
use Noodlehaus\Exception\UnsupportedFormatException;

/**
 * PHP file parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Php implements FileParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a PHP file and gets its' contents as an array
     *
     * @throws ParseException             If the PHP file throws an exception
     * @throws UnsupportedFormatException If the PHP file does not return an array
     */
    public function parse($path)
    {
        // Require the file, if it throws an exception, rethrow it
        try {
            $temp = require $path;
        } catch (Exception $exception) {
            throw new ParseException(
                array(
                    'message'   => 'PHP file threw an exception',
                    'exception' => $exception,
                )
            );
        }

        // If we have a callable, run it and expect an array back
        if (is_callable($temp)) {
            $temp = call_user_func($temp);
        }

        // Check for array, if its anything else, throw an exception
        if (!is_array($temp)) {
            throw new UnsupportedFormatException('PHP file does not return an array');
        }

        return $temp;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedExtensions()
    {
        return array('php');
    }
}
