<?php

namespace Noodlehaus\Writer;

use Noodlehaus\Exception\WriteException;
use Symfony\Component\Yaml\Yaml as YamlParser;

/**
 * Yaml Writer.
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @author     Mark de Groot <mail@markdegroot.nl>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Yaml implements WriterInterface
{
    /**
     * {@inheritdoc}
     * Writes an array to a Yaml file.
     */
    public function toFile($config, $filename)
    {
        $data = $this->toString($config);
        $success = @file_put_contents($filename, $data);
        if ($success === false) {
            throw new WriteException(['file' => $filename]);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     * Writes an array to a Yaml string.
     */
    public function toString($config)
    {
        return YamlParser::dump($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions()
    {
        return ['yaml'];
    }
}
