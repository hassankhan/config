<?php

namespace Noodlehaus\StringParser;

use Exception;
use Symfony\Component\Yaml\Yaml as YamlParser;
use Noodlehaus\Exception\ParseException;

/**
 * YAML string parser
 *
 * @package    Config
 * @author     Filip Š <projects@filips.si>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Yaml implements StringParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a YAML/YML string as an array
     *
     * @throws ParseException If If there is an error parsing the YAML string
     */
    public function parse($configuration)
    {
        try {
            $data = YamlParser::parse($configuration);
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
}
