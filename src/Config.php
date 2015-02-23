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
    private $supportedFileLoaders = array(
        'Noodlehaus\File\Php'  => array('php'),
        'Noodlehaus\File\Ini'  => array('ini'),
        'Noodlehaus\File\Json' => array('json'),
        'Noodlehaus\File\Xml'  => array('xml'),
        'Noodlehaus\File\Yaml' => array('yaml', 'yml'),
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
     * @throws FileNotFoundException      If a file is not found at `$path`
     * @throws UnsupportedFormatException If `$path` is an unsupported file format
     * @throws EmptyDirectoryException    If `$path` is an empty directory
     */
    public function __construct($path)
    {
        $paths      = $this->getValidPath($path);
        $this->data = array();

        foreach ($paths as $path) {

            // Get file information
            $info      = pathinfo($path);
            $extension = isset($info['extension']) ? $info['extension'] : '';
            // Holds the file loader for this config file
            $loader    = null;

            // Check if config file exists or throw an exception
            if (!file_exists($path)) {
                throw new FileNotFoundException("Configuration file: [$path] cannot be found");
            }

            // Create a loader object for the file extension
            $supportedFileFormats = array_values($this->supportedFileLoaders);
            foreach ($supportedFileFormats as $supportedFileExtension) {
                if (in_array(strtolower($extension), $supportedFileExtension)) {
                    $loaderName = array_search($supportedFileExtension, $this->supportedFileLoaders);
                    $loader     = new $loaderName();
                }
            }

            // If none exist, then throw an exception
            if ($loader === null) {
                throw new UnsupportedFormatException('Unsupported configuration format');
            }

            // Try and load file
            $this->data = array_replace_recursive($this->data, $loader->load($path));
        }
    }

    /**
     * Checks `$path` to see if it is either an array, a directory, or a file
     *
     * @param  string $path
     *
     * @return array
     *
     * @throws EmptyDirectoryException If `$path` is an empty directory
     */
    private function getValidPath($path)
    {
        // If `$path` is array
        if (is_array($path)) {
            $paths = array();
            foreach ($path as $unverifiedPath) {
                $paths = array_merge($paths, $this->getValidPath($unverifiedPath));
            }
            return $paths;
        }

        // If `$path` is a directory
        if (is_dir($path)) {
            $paths = glob($path . '/*.*');
            if (empty($paths)) {
                throw new EmptyDirectoryException("Configuration directory: [$path] is empty");
            }
            return $paths;
        }

        // If `$path` is a file
        return array($path);
    }
}
