<?php

namespace Noodlehaus\StringParser;

/**
 * Config string parser interface
 *
 * @package    Config
 * @author     Filip Š <projects@filips.si>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
interface StringParserInterface
{
    /**
     * Parses a configuration from `$configuration` and gets its contents as an array
     *
     * @param  string $configuration
     *
     * @return array
     */
    public function parse($configuration);
}
