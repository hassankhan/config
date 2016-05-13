<?php

namespace Noodlehaus\FileParser;

use Exception;
use Nette\Neon\Neon as NetteNeon;
use Noodlehaus\Exception\ParseException;

/**
 * NEON file parser
 *
 * @package    Config
 * @author     oNeDaL <onedal@voodoo.technology>
 * @link       https://github.com/onedal88/config
 * @license    MIT
 */
class Neon implements FileParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a NEON file as an array
     *
     * @throws ParseException If If there is an error parsing the NEON file
     */
    public function parse($path)
    {
        try {
            $data = NetteNeon::decode(file_get_contents($path));
        } catch (Exception $exception) {
            throw new ParseException(
                array(
                    'message'   => 'Error parsing NEON file',
                    'exception' => $exception,
                )
            );
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedExtensions()
    {
        return array('neon');
    }
}
