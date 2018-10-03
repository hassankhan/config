<?php

namespace Noodlehaus\Parser;

use Exception;
use Noodlehaus\Exception\ParseException;
use Noodlehaus\Exception\UnsupportedFormatException;

/**
 * PHP parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Php implements ParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a PHP string and gets its' contents as an array
     *
     * @throws ParseException             If the PHP string throws an exception
     * @throws UnsupportedFormatException If the PHP string does not return an array
     */
    public function parse($config, $filename = null)
    {
        // Strip PHP start and end tags
        $config = str_replace('<?php', '', $config);
        $config = str_replace('<?', '', $config);
        $config = str_replace('?>', '', $config);

        // Eval the string, if it throws an exception, rethrow it
        try {
            $temp = eval($config);
        } catch (Exception $exception) {
            throw new ParseException(
                [
                    'message'   => 'PHP string threw an exception',
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
            throw new UnsupportedFormatException('PHP string does not return an array');
        }

        return $temp;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedExtensions()
    {
        return ['php'];
    }
}
