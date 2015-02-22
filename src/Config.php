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
    * @return void
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
            $load_method = 'load' . ucfirst($info['extension']);
            if (!method_exists(__CLASS__, $load_method)) {
                throw new UnsupportedFormatException('Unsupported configuration format');
            }

            // Try and load file
            $this->data = array_replace_recursive($this->data, $this->$load_method($path));
        }
    }

    /**
     * Loads a PHP file and gets its' contents as an array
     *
     * @param  string $path
     *
     * @return array
     *
     * @throws ParseException             If the PHP file throws an exception
     * @throws UnsupportedFormatException If the PHP file does not return an array
     */
    protected function loadPhp($path)
    {
        $php = new Php();
        return $php->load($path);
    }

    /**
     * Loads an INI file as an array
     *
     * @param  string $path
     *
     * @return array
     *
     * @throws ParseException If there is an error parsing the INI file
     */
    protected function loadIni($path)
    {
        $ini = new Ini();
        return $ini->load($path);
    }

    /**
     * Loads a JSON file as an array
     *
     * @param  string $path
     *
     * @return array
     *
     * @throws ParseException If there is an error parsing the JSON file
     */
    protected function loadJson($path)
    {
        $json = new Json();
        return $json->load($path);
    }


    /**
     * Loads a XML file as an array
     *
     * @param  string $path
     *
     * @return array
     *
     * @throws ParseException If there is an error parsing the XML file
     */
    protected function loadXml($path)
    {
        $xml = new Xml();
        return $xml->load($path);
    }

    /**
     * Loads a YAML file as an array
     *
     * @param  string $path
     *
     * @return array
     *
     * @throws ParseException If If there is an error parsing the YAML file
     */
    protected function loadYaml($path)
    {
        $yaml = new Yaml();
        return $yaml->load($path);
    }

    /**
     * Alias method for `loadYaml()`
     *
     * @param  string $path
     *
     * @return array
     *
     * @throws ParseException If If there is an error parsing the YML file
     */
    protected function loadYml($path)
    {
        return $this->loadYaml(file_get_contents($path));
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
