<?php

namespace Noodlehaus\StringParser;

use Exception;
use Noodlehaus\Exception\ParseException;
use Noodlehaus\Exception\UnsupportedFormatException;

/**
 * PHP string parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Php implements StringParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a PHP string and gets its' contents as an array
     *
     * @throws ParseException             If the PHP file throws an exception
     * @throws UnsupportedFormatException If the PHP file does not return an array
     */
    public function parse($configuration)
    {
        // Strip PHP start and end tags
        $configuration = str_replace('<?php', '', $configuration);
        $configuration = str_replace('<?', '', $configuration);
        $configuration = str_replace('?>', '', $configuration);

        // Eval the string, if it throws an exception, rethrow it
        try {
            $temp = eval($configuration);
        } catch (Exception $exception) {
            throw new ParseException(
                [
                    'message'   => 'PHP file threw an exception',
                    'exception' => $exception,
                ]
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
}
