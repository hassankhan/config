<?php

namespace Noodlehaus\StringParser;

/**
 * Abstract string parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
abstract class AbstractStringParser implements StringParserInterface
{

    /**
     * String with configuration
     *
     * @var string
     */
    protected $configuration;

    /**
     * Sets the string with configuration
     *
     * @param string $configuration
     *
     * @codeCoverageIgnore
     */
    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }
}
