<?php

namespace Noodlehaus\Parser;

use Noodlehaus\Exception\ParseException;

/**
 * Class Serialize
 *
 * @package Config
 */
class Serialize implements ParserInterface
{

    /**
     * {@inheritdoc}
     */
    public function parseFile($filename)
    {
        $data = file_get_contents($filename);

        return (array) $this->parse($data, $filename);
    }

    /**
     * {@inheritdoc}
     */
    public function parseString($config)
    {
        return (array) $this->parse($config);
    }


    /**
     * Completes parsing of JSON data
     *
     * @param  string  $data
     * @param  string $filename
     * @return array|null
     *
     * @throws ParseException If there is an error parsing the serialized data
     */
    protected function parse($data = null, $filename = null)
    {
        $serializedData = @unserialize($data);
        if($serializedData === false){

            throw new ParseException(error_get_last());
        }

        return $serializedData;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions()
    {
        return ['txt'];
    }
}
