<?php

namespace Noodlehaus\Parser;

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
        return $this->parseString($data);
    }

    /**
     * {@inheritdoc}
     */
    public function parseString($config)
    {
        return (array) unserialize($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions()
    {
        return ['txt'];
    }
}
