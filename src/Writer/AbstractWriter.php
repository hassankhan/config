<?php

namespace Noodlehaus\Writer;

use Noodlehaus\Exception\WriteException;

/**
 * Base Writer.
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @author     Mark de Groot <mail@markdegroot.nl>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
abstract class AbstractWriter implements WriterInterface
{
    /**
     * {@inheritdoc}
     */
    public function toFile($config, $filename)
    {
        $contents = $this->toString($config);
        $success = @file_put_contents($filename, $contents);
        if ($success === false) {
            throw new WriteException(['file' => $filename]);
        }

        return $contents;
    }
}
