<?php

namespace Noodlehaus\FileParser;

use Exception;
use Symfony\Component\Yaml\Yaml as YamlParser;
use Noodlehaus\Exception\ParseException;

/**
 * YAML file parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Yaml implements FileParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a YAML/YML file as an array
     *
     * @throws ParseException If If there is an error parsing the YAML file
     */
    public function parse($path)
    {
        try {
            $data = YamlParser::parse(file_get_contents($path), YamlParser::PARSE_CONSTANT);
        } catch (Exception $exception) {
            throw new ParseException(
                array(
                    'message'   => 'Error parsing YAML file',
                    'exception' => $exception,
                )
            );
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedExtensions()
    {
        return array('yaml', 'yml');
    }
}
