<?php

namespace Noodlehaus\File;

use Noodlehaus\Exception\ParseException;

/**
 * INI file loader
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Ini implements FileInterface
{
    /**
     * {@inheritDoc}
     * Loads an INI file as an array
     *
     * @throws ParseException If there is an error parsing the INI file
     */
    public function load($path)
    {
        $data = @parse_ini_file($path, true);

        if (!$data) {
            $error = error_get_last();
            throw new ParseException($error);
        }

        return $data;
    }
}
