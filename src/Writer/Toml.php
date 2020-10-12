<?php

namespace Noodlehaus\Writer;

use Yosymfony\Toml\TomlBuilder;

/**
 * Toml Writer.
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @author     Mark de Groot <mail@markdegroot.nl>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Toml extends AbstractWriter
{
    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions()
    {
        return ['toml'];
    }

    /**
     * {@inheritdoc}
     * Writes an array to a Toml string.
     */
    public function toString($config, $pretty = true)
    {
        $builder = new TomlBuilder();
        $assoc = [];

        foreach ($config as $key => $value) {
            if ($this->isAssoc($value)) {
                $assoc[$key] = $value;
            } else {
                $builder->addValue($key, $value);
            }
        }

        foreach ($assoc as $key => $value) {
            $this->walk($key, $value, $builder);
        }

        return $builder->getTomlString();
    }

    protected function walk($key, $value, TomlBuilder $builder, $parent = '')
    {
        if ($this->isAssoc($value)) {
            $key = empty($parent) ? $key : "$parent.$key";
            $builder->addTable($key);

            foreach ($value as $_key => $_value) {
                $this->walk($_key, $_value, $builder, $key);
            }
        } else {
            $builder->addValue($key, $value);
        }
    }

    private function isAssoc($arr)
    {
        return is_array($arr) && array_diff_key($arr, array_keys(array_keys($arr)));
    }
}
