<?php

namespace Noodlehaus\Parser;

use Noodlehaus\Exception\ParseException;
use Yosymfony\Toml\Toml as TomlParser;

/**
 * TOML parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Toml implements ParserInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSupportedExtensions()
    {
        return ['toml'];
    }

    /**
     * {@inheritDoc}
     * Loads a TOML file as an array
     *
     * @throws ParseException If there is an error parsing the TOML file
     */
    public function parseFile($filename)
    {
        try {
            return TomlParser::parseFile($filename);
        } catch (\Exception $exception) {
            throw new ParseException(
                [
                    'message' => 'Error parsing TOML file',
                    'exception' => $exception,
                ]
            );
        }
    }

    /**
     * {@inheritDoc}
     * Loads a TOML string as an array
     *
     * @throws ParseException If there is an error parsing the TOML string
     */
    public function parseString($config)
    {
        try {
            return TomlParser::parse($config);
        } catch (\Exception $exception) {
            throw new ParseException(
                [
                    'message' => 'Error parsing TOML string',
                    'exception' => $exception,
                ]
            );
        }
    }
}