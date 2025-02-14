<?php

namespace Noodlehaus\Parser;

/**
 * Abstract parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * String with configuration
     */
    protected string $config;

    /**
     * Sets the string with configuration
     *
     * @codeCoverageIgnore
     */
    public function __construct(string $config, ?string $filename = null)
    {
        $this->config = $config;
    }
}
