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
     * Loads a PHP file and gets its' contents as an array
     *
     * @throws ParseException             If the PHP file throws an exception
     * @throws UnsupportedFormatException If the PHP file does not return an array
     */
    public function parseFile($filename)
    {
        // Run the fileEval the string, if it throws an exception, rethrow it
        try {
            $data = require $filename;
        } catch (Exception $exception) {
            throw new ParseException(
                [
                    'message'   => 'PHP file threw an exception',
                    'exception' => $exception,
                ]
            );
        }

        // Complete parsing
        return (array)$this->parse($data, $filename);
    }

    /**
     * {@inheritDoc}
     * Loads a PHP string and gets its' contents as an array
     *
     * @throws ParseException             If the PHP string throws an exception
     * @throws UnsupportedFormatException If the PHP string does not return an array
     */
    public function parseString($config)
    {
        // Handle PHP start tag
        $config = trim($config);
        if (substr($config, 0, 2) === '<?') {
            $config = '?>' . $config;
        }

        // Eval the string, if it throws an exception, rethrow it
        try {
            $data = $this->isolate($config);
        } catch (Exception $exception) {
            throw new ParseException(
                [
                    'message'   => 'PHP string threw an exception',
                    'exception' => $exception,
                ]
            );
        }

        // Complete parsing
        return (array)$this->parse($data);
    }

    /**
     * Completes parsing of PHP data
     *
     * @param  array $data
     * @param  string $filename
     *
     * @return array|null
     * @throws UnsupportedFormatException
     */
    protected function parse($data = null, $filename = null)
    {
        // If we have a callable, run it and expect an array back
        if (is_callable($data)) {
            $data = call_user_func($data);
        }

        // Check for array, if its anything else, throw an exception
        if (!is_array($data)) {
            throw new UnsupportedFormatException('PHP data does not return an array');
        }

        return $data;
    }

    /**
     * Runs PHP string in isolated method
     *
     * @param  string $EGsfKPdue7ahnMTy
     *
     * @return array
     */
    protected function isolate($EGsfKPdue7ahnMTy)
    {
        return eval($EGsfKPdue7ahnMTy);
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedExtensions()
    {
        return ['php'];
    }
}
