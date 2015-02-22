<?php

namespace Noodlehaus\File;

/**
 * Config File interface
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
interface FileInterface
{

    /**
     * Loads a file from `$path` and gets its' contents as an array
     *
     * @param  string $key
     *
     * @return array
     */
    public function load($path);
}
