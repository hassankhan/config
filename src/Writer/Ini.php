<?php

namespace Noodlehaus\Writer;

/**
 * Ini Writer.
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @author     Mark de Groot <mail@markdegroot.nl>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Ini extends AbstractWriter
{
    /**
     * {@inheritdoc}
     * Writes an array to a Ini string.
     */
    public function toString($config, $pretty = true)
    {
        return $this->toINI($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions()
    {
        return ['ini', 'properties'];
    }

    /**
     * Converts array to INI string.
     * @param array $arr    Array to be converted
     * @param array $parent Parent array
     *
     * @return string Converted array as INI
     *
     * @see https://stackoverflow.com/a/17317168/6523409/
     */
    protected function toINI(array $arr, array $parent = [])
    {
        $converted = '';

        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $sec = array_merge((array) $parent, (array) $k);
                $converted .= '['.implode('.', $sec).']'.PHP_EOL;
                $converted .= $this->toINI($v, $sec);
            } else {
                $converted .= $k.'='.$v.PHP_EOL;
            }
        }

        return $converted;
    }
}
