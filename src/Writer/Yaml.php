<?php

namespace Noodlehaus\Writer;

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
class Yaml extends AbstractWriter
{
    /**
     * {@inheritdoc}
     * Writes an array to a Yaml string.
     */
    public function toString($config, $pretty = true)
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
