<?php

namespace Noodlehaus\Writer;

/**
 * Properties Writer.
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @author     Mark de Groot <mail@markdegroot.nl>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Properties extends AbstractWriter
{
    /**
     * {@inheritdoc}
     * Writes an array to a Properties string.
     */
    public function toString(array $config, bool $pretty = true): string
    {
        return $this->toProperties($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions(): array
    {
        return ['properties'];
    }

    /**
     * Converts array to Properties string.
     */
    protected function toProperties(array $arr): string
    {
        $converted = '';

        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            // Escape all space, ; and = characters in the key:
            $key = addcslashes($key, ' :=');

            // Escape all backslashes and newlines in the value:
            $value = preg_replace('/([\r\n\t\f\v\\\])/', '\\\$1', $value);

            $converted .= $key.' = '.$value.PHP_EOL;
        }

        return $converted;
    }
}
