<?php

namespace Noodlehaus\Writer;

use Noodlehaus\Exception\WriteException;

/**
 * JSON Writer.
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @author     Mark de Groot <mail@markdegroot.nl>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Json extends AbstractWriter
{
    /**
     * {@inheritdoc}
     * Writes an array to a JSON file.
     */
    public function toFile(array $config, string $filename): string
    {
        $data = $this->toString($config);
        $success = @file_put_contents($filename, $data.PHP_EOL);
        if ($success === false) {
            throw new WriteException(['file' => $filename]);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     * Writes an array to a JSON string.
     */
    public function toString(array $config, bool $pretty = true): string
    {
        return json_encode($config, $pretty ? (JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) : 0);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions(): array
    {
        return ['json'];
    }
}
