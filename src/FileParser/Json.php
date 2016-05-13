<?php

namespace Noodlehaus\FileParser;

use Noodlehaus\Exception\ParseException;

/**
 * JSON file parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Json implements FileParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a JSON file as an array
     *
     * @throws ParseException If there is an error parsing the JSON file
     */
    public function parse($path)
    {
        $data = json_decode(file_get_contents($path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = array(
                'message' => 'Syntax error',
                'type'    => json_last_error(),
                'file'    => $path,
            );
            throw new ParseException($error);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedExtensions()
    {
        return array('json');
    }
}
