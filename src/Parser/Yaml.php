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
     * Loads a YAML/YML file as an array
     *
     * @throws ParseException If there is an error parsing the YAML file
     */
    public function parseFile($filename)
    {
        try {
            $data = YamlParser::parseFile($filename, YamlParser::PARSE_CONSTANT);
        } catch (Exception $exception) {
            throw new ParseException(
                [
                    'message'   => 'Error parsing YAML file',
                    'exception' => $exception,
                ]
            );
        }

        return (array)$this->parse($data);
    }

    /**
     * {@inheritDoc}
     * Loads a YAML/YML string as an array
     *
     * @throws ParseException If If there is an error parsing the YAML string
     */
    public function parseString($config)
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

        return (array)$this->parse($data);
    }

    /**
     * Completes parsing of YAML/YML data
     *
     * @param  array $data
     *
     * @return array|null
     */
    protected function parse($data = null)
    {
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
