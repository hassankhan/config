<?php

namespace Noodlehaus;

use Noodlehaus\Exception\ParseException;
use Noodlehaus\Exception\FileNotFoundException;
use Noodlehaus\Exception\UnsupportedFormatException;
use Noodlehaus\Exception\EmptyDirectoryException;
use \Symfony\Component\Yaml\Yaml;

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
     * Stores the configuration data
     *
     * @var array|null
     */
    protected $data = null;

    /**
     * Caches the configuration data
     *
     * @var array
     */
    protected $cache = array();

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
        // Require the file, if it throws an exception, rethrow it
        try {
            $temp = require $path;
        }
        catch (\Exception $ex) {
            throw new ParseException(
                array(
                    'message'   => 'PHP file threw an exception',
                    'exception' => $ex
                )
            );
        }

        // If we have a callable, run it and expect an array back
        if (is_callable($temp)) {
            $temp = call_user_func($temp);
        }

        // Check for array, if its anything else, throw an exception
        if (!$temp || !is_array($temp)) {
            throw new UnsupportedFormatException('PHP file does not return an array');
        }

        return $temp;
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
        $data = @parse_ini_file($path, true);

        if (!$data) {
            $error = error_get_last();
            throw new ParseException($error);
        }

        return $data;
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
        $data = json_decode(file_get_contents($path), true);

        if (function_exists('json_last_error_msg')) {
            $error_message = json_last_error_msg();
        } else {
            $error_message  = 'Syntax error';
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = array(
                'message' => $error_message,
                'type'    => json_last_error(),
                'file'    => $path
            );
            throw new ParseException($error);
        }

        return $data;
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
        libxml_use_internal_errors(true);

        $data = simplexml_load_file($path, null, LIBXML_NOERROR);

        if ($data === false) {
            $errors      = libxml_get_errors();
            $latestError = array_pop($errors);
            $error       = array(
                'message' => $latestError->message,
                'type'    => $latestError->level,
                'code'    => $latestError->code,
                'file'    => $latestError->file,
                'line'    => $latestError->line
            );
            throw new ParseException($error);
        }

        $data = json_decode(json_encode($data), true);

        return $data;
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
        try {
            $data = Yaml::parse($path);
        }
        catch(\Exception $ex) {
            throw new ParseException(
                array(
                    'message'   => 'Error parsing YAML file',
                    'exception' => $ex
                )
            );
        }

        return $data;
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
}
