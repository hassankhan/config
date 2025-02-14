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
    public function parseFile(string $filename): array
    {
        $data = file_get_contents($filename);

        return (array) $this->parse($data, $filename);
    }

    /**
     * {@inheritdoc}
     */
    public function parseString(string $config): array
    {
        return (array) $this->parse($config);
    }

    /**
     * Completes parsing of JSON data
     *
     * @throws ParseException If there is an error parsing the serialized data
     */
    protected function parse(string $data, ?string $filename = null): ?array
    {
        $serializedData = @unserialize($data);

        if ($serializedData === false) {
            throw new ParseException(error_get_last());
        }

        return $serializedData;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions(): array
    {
        return ['txt'];
    }
}
