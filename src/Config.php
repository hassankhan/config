<?php

namespace Noodlehaus;

use Noodlehaus\Exception\FileNotFoundException;
use Noodlehaus\Exception\UnsupportedFormatException;
use Noodlehaus\Exception\EmptyDirectoryException;

/**
 * Config
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Config extends AbstractConfig
{
    /**
     * All file formats supported by Config
     *
     * @var array
     */
    private $supportedFileParsers = array(
        'Noodlehaus\FileParser\Php',
        'Noodlehaus\FileParser\Ini',
        'Noodlehaus\FileParser\Json',
        'Noodlehaus\FileParser\Xml',
        'Noodlehaus\FileParser\Yaml'
    );

    /**
     * Static method for loading a Config instance.
     *
     * @param  string|array $path
     *
     * @return Config
     */
    public static function load($path)
    {
        return new static($path);
    }

    /**
     * Loads a supported configuration file format.
     *
     * @param  string|array $path
     *
     * @throws EmptyDirectoryException    If `$path` is an empty directory
     */
    public function __construct($path)
    {
        $paths      = $this->getValidPath($path);
        $this->data = array();

        foreach ($paths as $path) {

            // Get file information
            $info      = pathinfo($path);
            $parts = explode('.', $info['basename']);
            $extension = array_pop($parts);
            if ($extension === 'dist') {
                $extension = array_pop($parts);
            }
            $parser    = $this->getParser($extension);

            // Try and load file
            $this->data = array_replace_recursive($this->data, (array) $parser->parse($path));
        }

        parent::__construct($this->data);
    }

    /**
     * Gets a parser for a given file extension
     *
     * @param  string $extension
     *
     * @return Noodlehaus\FileParser\FileParserInterface
     *
     * @throws UnsupportedFormatException If `$path` is an unsupported file format
     */
    private function getParser($extension)
    {
        foreach ($this->supportedFileParsers as $fileParser) {
            if (in_array($extension, $fileParser::getSupportedExtensions($extension))) {
                return new $fileParser();
            }

        }

        // If none exist, then throw an exception
        throw new UnsupportedFormatException('Unsupported configuration format');
    }

    /**
     * Gets an array of paths
     *
     * @param  array $path
     *
     * @return array
     *
     * @throws FileNotFoundException   If a file is not found at `$path`
     */
    private function getPathFromArray($path)
    {
        $paths = array();

        foreach ($path as $unverifiedPath) {
            try {
                // Check if `$unverifiedPath` is optional
                // If it exists, then it's added to the list
                // If it doesn't, it throws an exception which we catch
                if ($unverifiedPath[0] !== '?') {
                    $paths = array_merge($paths, $this->getValidPath($unverifiedPath));
                    continue;
                }
                $optionalPath = ltrim($unverifiedPath, '?');
                $paths = array_merge($paths, $this->getValidPath($optionalPath));

            } catch (FileNotFoundException $e) {
                // If `$unverifiedPath` is optional, then skip it
                if ($unverifiedPath[0] === '?') {
                    continue;
                }
                // Otherwise rethrow the exception
                throw $e;
            }
        }

        return $paths;
    }

    /**
     * Checks `$path` to see if it is either an array, a directory, or a file
     *
     * @param  string|array $path
     *
     * @return array
     *
     * @throws EmptyDirectoryException If `$path` is an empty directory
     *
     * @throws FileNotFoundException   If a file is not found at `$path`
     */
    private function getValidPath($path)
    {
        // If `$path` is array
        if (is_array($path)) {
            return $this->getPathFromArray($path);
        }

        // If `$path` is a directory
        if (is_dir($path)) {
            $paths = glob($path . '/*.*');
            if (empty($paths)) {
                throw new EmptyDirectoryException("Configuration directory: [$path] is empty");
            }
            return $paths;
        }

        // If `$path` is not a file, throw an exception
        if (!file_exists($path)) {
            throw new FileNotFoundException("Configuration file: [$path] cannot be found");
        }
        return array($path);
    }
}
