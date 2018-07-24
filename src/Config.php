<?php

namespace Noodlehaus;

use Noodlehaus\Exception\FileNotFoundException;
use Noodlehaus\Exception\UnsupportedFormatException;
use Noodlehaus\Exception\EmptyDirectoryException;
use InvalidArgumentException;
use Noodlehaus\FileParser\FileParserInterface;
use Noodlehaus\StringParser\StringParserInterface;

/**
 * Configuration reader and writer for PHP.
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
     * All file formats supported by Config.
     *
     * @var array
     */
    protected $supportedFileParsers = [
        'Noodlehaus\FileParser\Php',
        'Noodlehaus\FileParser\Ini',
        'Noodlehaus\FileParser\Json',
        'Noodlehaus\FileParser\Xml',
        'Noodlehaus\FileParser\Yaml'
    ];

    /**
     * All string formats supported by Config.
     *
     * @var array
     */
    protected $supportedStringParsers = [
        'Noodlehaus\StringParser\Php',
        'Noodlehaus\StringParser\Ini',
        'Noodlehaus\StringParser\Json',
        'Noodlehaus\StringParser\Xml',
        'Noodlehaus\StringParser\Yaml'
    ];

    /**
     * Static method for loading a Config instance.
     *
     * @param  string|array                              $values Filenames or string with configuration
     * @param  FileParserInterface|StringParserInterface $parser Configuration parser
     *
     * @return Config
     */
    public static function load($values, $parser = null)
    {
        return new static($values, $parser);
    }

    /**
     * Loads a Config instance.
     *
     * @param  string|array                              $values Filenames or string with configuration
     * @param  FileParserInterface|StringParserInterface $parser Configuration parser
     *
     * @throws InvalidArgumentException If `$parser` is not implementing correct interface
     */
    public function __construct($values, $parser = null)
    {
        if ($parser instanceof FileParserInterface || $parser === null) {
            $this->loadFromFile($values, $parser);
        } elseif ($parser instanceof StringParserInterface) {
            $this->loadFromString($values, $parser);
        } else {
            throw new InvalidArgumentException('Parser not implementing correct interface');
        }

        parent::__construct($this->data);
    }

    /**
     * Loads configuration from file.
     *
     * @param  string|array         $path   Filenames or directories with configuration
     * @param  FileParserInterface  $parser Configuration parser
     *
     * @throws EmptyDirectoryException If `$path` is an empty directory
     */
    protected function loadFromFile($path, FileParserInterface $parser = null)
    {
        $paths      = $this->getValidPath($path);
        $this->data = [];

        foreach ($paths as $path) {
            if ($parser === null) {
                // Get file information
                $info      = pathinfo($path);
                $parts     = explode('.', $info['basename']);
                $extension = array_pop($parts);

                // Skip the `dist` extension
                if ($extension === 'dist') {
                    $extension = array_pop($parts);
                }

                // Get file parser
                $parser = $this->getParser($extension);

                // Try to load file
                $this->data = array_replace_recursive($this->data, (array) $parser->parse($path));

                // Clean parser
                $parser = null;
            } else {
                // Try to load file using specified parser
                $this->data = array_replace_recursive($this->data, (array) $parser->parse($path));
            }
        }
    }

    /**
     * Loads configuration from string.
     *
     * @param string                $configuration String with configuration
     * @param StringParserInterface $parser        Configuration parser
     */
    protected function loadFromString($configuration, StringParserInterface $parser)
    {
        $this->data = [];

        // Try to parse string
        $this->data = array_replace_recursive($this->data, (array) $parser->parse($configuration));
    }

    /**
     * Gets a parser for a given file extension.
     *
     * @param  string $extension
     *
     * @return Noodlehaus\FileParser\FileParserInterface
     *
     * @throws UnsupportedFormatException If `$extension` is an unsupported file format
     */
    protected function getParser($extension)
    {
        foreach ($this->supportedFileParsers as $fileParser) {
            if (in_array($extension, $fileParser::getSupportedExtensions())) {
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
    protected function getPathFromArray($path)
    {
        $paths = [];

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
     * Checks `$path` to see if it is either an array, a directory, or a file.
     *
     * @param  string|array $path
     *
     * @return array
     *
     * @throws EmptyDirectoryException If `$path` is an empty directory
     *
     * @throws FileNotFoundException   If a file is not found at `$path`
     */
    protected function getValidPath($path)
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

        return [$path];
    }
}
