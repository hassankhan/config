<?php

namespace Noodlehaus\Writer;

/**
 * Class Serialize
 *
 * @package Config
 */
class Serialize extends AbstractWriter
{

    /**
     * {@inheritdoc}
     */
    public function toString(array $config, bool $pretty = true): string
    {
        return serialize($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions(): array
    {
        return ['txt'];
    }
}
