<?php

namespace Noodlehaus\Writer;

use Noodlehaus\Exception\WriteException;

/**
 * Config file parser interface.
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @author     Mark de Groot <mail@markdegroot.nl>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
interface WriterInterface
{
    /**
     * Writes a configuration from `$config` to `$filename`.
     *
     *@throws WriteException if the data could not be written to the file
     */
    public function toFile(array $config, string $filename): string;

    /**
     * Writes a configuration from `$config` to a string.
     */
    public function toString(array $config, bool $pretty = true): string;

    /**
     * Returns an array of allowed file extensions for this writer.
     */
    public static function getSupportedExtensions(): array;
}
