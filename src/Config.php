<?php

namespace Noodlehaus;

use Noodlehaus\File\Php;
use Noodlehaus\File\Ini;
use Noodlehaus\File\Json;
use Noodlehaus\File\Xml;
use Noodlehaus\File\Yaml;
use Noodlehaus\Exception\ParseException;
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

    private $supportedFileFormats = array(
        'PHP',
        'INI',
        'JSON',
        'XML',
        'YAML',
        'YML'
    );

    /**
    * Static method for loading a config instance.
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
        $paths      = $this->_getValidPath($path);
        $this->data = array();

        foreach($paths as $path){
            // Get file information
            $info = pathinfo($path);

            // Check if config file exists or throw an exception
            if (!file_exists($path)) {
                throw new FileNotFoundException("Configuration file: [$path] cannot be found");
            }

            // Check if a load-* method exists for the file extension, if not throw exception
            if (!in_array(strtoupper($info['extension']), $this->supportedFileFormats)) {
                throw new UnsupportedFormatException('Unsupported configuration format');
            }

            $extension = $info['extension'];
            // Check if extension is YML, replace with YAML
            if (strtolower($extension) === 'yml') {
                $extension = 'yaml';
            }

            $loaderName = 'Noodlehaus\\File\\' . ucfirst($extension);
            $loader = new $loaderName;

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
     * @throws EmptyDirectoryException    If `$path` is an empty directory
     */
    private function _getValidPath($path)
    {
        // If `$path` is array
        if (is_array($path)) {
            $paths = array();
            foreach ($path as $unverifiedPath) {
                $paths = array_merge($paths, $this->_getValidPath($unverifiedPath));
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
