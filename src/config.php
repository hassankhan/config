<?php

namespace Noodlehaus;

/**
 * Config
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Config {

    /**
     * Stores the configuration data
     * @var array|null
     */
    private $data = null;

    /**
     * Caches the configuration data
     * @var array
     */
    private $cache = array();

    /**
    * Static method for loading a config instance.
    *
    * @param string $path
    *
    * @return Config
    */
    public static function load($path)
    {
        return new Config($path);
    }

    /**
    * Loads a supported configuration file format.
    *
    * @param string $path
    *
    * @return Config
    */
    public function __construct($path)
    {
        // Get file information
        $info = pathinfo($path);

        // Check if config file exists or throw an exception
        if (!file_exists($path)) {
            throw new \Exception("Configuration file: [$path] cannot be found");
        }

        // Check if a load-* method exists for the file extension, if not throw exception
        $load_method = 'load' . ucfirst($info['extension']);
        if (!method_exists(__CLASS__, $load_method)) {
            throw new \Exception('Unsupported configuration format');
        }

        // Try and load file
        $this->data = $this->$load_method($path);

    }

    /**
     * Loads a PHP file and gets its' contents as an array
     *
     * @param  string $path
     *
     * @return array
     */
    protected function loadPhp($path)
    {
        // Keep it quiet and rethrow errors
        try {
            ob_start();
            $temp = require $path;
            ob_get_clean();
        }
        catch (\Exception $ex) {
            throw new Exception('PHP file threw an exception', 0, $ex);
        }

        // If we have a callable, run it and expect an array back
        if (is_callable($temp)) {
            $temp = call_user_func($temp);
        }

        // Check for array, if its anything else, throw an exception
        if (!$temp || !is_array($temp)) {
            throw new \Exception('PHP file does not return an array');
        }

        return $temp;
    }

    /**
     * Loads an INI file as an array
     *
     * @param  string $path
     *
     * @return array
     */
    protected function loadIni($path)
    {
        $data = @parse_ini_file($path, true);

        if (!$data) {
        throw new \Exception('INI parse error');
        }

        return $data;
    }

    /**
     * Loads a JSON file as an array
     *
     * @param  string $path
     *
     * @return array
     */
    protected function loadJson($path)
    {
        $data = json_decode(file_get_contents($path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON parse error');
        }

        return $data;
    }

    /**
    * Gets a configuration setting using a simple or nested key.
    * Nested keys are similar to JSON paths that use the dot
    * dot notation.
    *
    * @param string $key
    * @param mixed  $default
    *
    * @return mixed
    */
    public function get($key, $default = null) {

        // Check if already cached
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $segs = explode('.', $key);
        $root = $this->data;

        // nested case
        foreach ($segs as $part) {
            if (isset($root[$part])){
                $root = $root[$part];
                continue;
            }
            else {
                $root = $default;
                break;
            }
        }

        // whatever we have is what we needed
        return ($this->cache[$key] = $root);
    }

    /**
    * Function for setting configuration values, using
    * either simple or nested keys.
    *
    * @param string $key
    * @param mixed  $value
    *
    * @return void
    */
    public function set($key, $value) {

        $segs = explode('.', $key);
        $root = &$this->data;

        // Look for the key, creating nesting if needed
        while ($part = array_shift($segs)) {
            if (!isset($root[$part]) && count($segs)) {
                $root[$part] = array();
            }
            $root = &$root[$part];
        }

        // Assign value at target node
        $root = $value;

        // Invalidate or create cache entry
        if ($root === null) {
            unset($this->cache[$key]);
        }
        else {
            $this->cache[$key] = $root;
        }
    }

}
