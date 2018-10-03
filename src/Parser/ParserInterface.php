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
     * Parses a configuration from `$config` and gets its contents as an array
     *
     * @param  string $config
     * @param  string $filename
     *
     * @return array
     */
    public function parse($config, $filename = null);

    /**
     * Returns an array of allowed file extensions for this parser
     *
     * @return array
     */
    public static function getSupportedExtensions();
}
