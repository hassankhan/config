<?php

namespace Noodlehaus\Parser;

use Noodlehaus\Exception\ParseException;

/**
 * XML parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Xml implements ParserInterface
{
    /**
     * {@inheritDoc}
     * Parses an XML file as an array
     *
     * @throws ParseException If there is an error parsing the XML file
     */
    public function parseFile(string $filename): array
    {
        libxml_use_internal_errors(true);
        $data = simplexml_load_file($filename, null, LIBXML_NOERROR);

        return (array) $this->parse($data, $filename);
    }

    /**
     * {@inheritDoc}
     * Parses an XML string as an array
     *
     * @throws ParseException If there is an error parsing the XML string
     */
    public function parseString(string $config): array
    {
        libxml_use_internal_errors(true);
        $data = simplexml_load_string($config, null, LIBXML_NOERROR);
        return (array) $this->parse($data);
    }

    /**
     * Completes parsing of XML data
     *
     * @param  \SimpleXMLElement|false|null $data
     * @param  string|null $filename
     *
     * @return array|null
     *
     * @throws ParseException If there is an error parsing the XML data
     */
    protected function parse($data = null, ?string $filename = null): ?array
    {
        if ($data === false) {
            $errors      = libxml_get_errors();
            $latestError = array_pop($errors);
            $error       = [
                'message' => $latestError->message,
                'type'    => $latestError->level,
                'code'    => $latestError->code,
                'file'    => $filename,
                'line'    => $latestError->line,
            ];
            throw new ParseException($error);
        }

        return json_decode(json_encode($data), true);
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedExtensions(): array
    {
        return ['xml'];
    }
}
