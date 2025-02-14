<?php

namespace Noodlehaus\Parser;

/**
 * Config file parser interface
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
interface ParserInterface
{
    /**
     * Parses a configuration from file `$filename` and gets its contents as an array
     */
    public function parseFile(string $filename): array;

    /**
     * Parses a configuration from string `$config` and gets its contents as an array
     */
    public function parseString(string $config): array;

    /**
     * Returns an array of allowed file extensions for this parser
     */
    public static function getSupportedExtensions(): array;
}
