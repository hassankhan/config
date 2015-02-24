<?php

namespace Noodlehaus\FileParser;

/**
 * Config File interface
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
interface FileParserInterface
{
    /**
     * Loads a file from `$path` and gets its contents as an array
     *
     * @param  string $path
     *
     * @return array
     */
    public function parse($path);
}
