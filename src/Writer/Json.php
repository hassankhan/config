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
class Json implements WriterInterface
{
    /**
     * {@inheritdoc}
     * Writes an array to a JSON file.
     */
    public function toFile($config, $filename)
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
    public function toString($config)
    {
        return json_encode($config, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions()
    {
        return ['json'];
    }
}
