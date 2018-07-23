<?php

namespace Noodlehaus\StringParser;

use Noodlehaus\Exception\ParseException;

/**
 * XML string parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Xml implements StringParserInterface
{
    /**
     * {@inheritDoc}
     * Parses an XML string as an array
     *
     * @throws ParseException If there is an error parsing the XML string
     */
    public function parse($configuration)
    {
        libxml_use_internal_errors(true);

        $data = simplexml_load_string($configuration, null, LIBXML_NOERROR);

        if ($data === false) {
            $errors      = libxml_get_errors();
            $latestError = array_pop($errors);
            $error       = [
                'message' => $latestError->message,
                'type'    => $latestError->level,
                'code'    => $latestError->code,
                'line'    => $latestError->line,
            ];
            throw new ParseException($error);
        }

        $data = json_decode(json_encode($data), true);

        return $data;
    }
}
