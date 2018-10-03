<?php

namespace Noodlehaus\Parser;

use Exception;
use Symfony\Component\Yaml\Yaml as YamlParser;
use Noodlehaus\Exception\ParseException;

/**
 * YAML parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Yaml implements ParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a YAML/YML string as an array
     *
     * @throws ParseException If If there is an error parsing the YAML string
     */
    public function parse($config, $filename = null)
    {
        try {
            $data = YamlParser::parse($config, YamlParser::PARSE_CONSTANT);
        } catch (Exception $exception) {
            throw new ParseException(
                [
                    'message'   => 'Error parsing YAML string',
                    'exception' => $exception,
                ]
            );
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedExtensions()
    {
        return ['yaml', 'yml'];
    }
}
