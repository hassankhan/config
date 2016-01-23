<?php

namespace Noodlehaus\FileParser;

/**
 * Abstract file parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
abstract class AbstractFileParser implements FileParserInterface
{

    /**
     * Path to the config file
     *
     * @var string
     */
    protected $path;

    /**
     * Sets the path to the config file
     *
     * @param string $path
     *
     * @codeCoverageIgnore
     */
    public function __construct($path)
    {
        $this->path = $path;
    }
}
