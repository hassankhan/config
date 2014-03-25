<?php
/**
 * @author Jesus A. Domingo <jesus.domingo@gmail.com>
 * @license MIT <http://noodlehaus.mit-license.org>
 */
namespace Noodlehaus;

class Config {

  private $data = null;
  private $cache = array();

  /**
   * Alternative way of loading a config instance.
   *
   * @param string $path config file to load.
   *
   * @return Config config instance loaded
   */
  public static function load($path) {
    return new Config($path);
  }

  /**
   * Constructor. Loads a supported configuration file
   * format.
   *
   * @param string $path config file path to load
   *
   * @return Config config instance loaded
   */
  public function __construct($path) {

    $info = pathinfo($path);
      
    //check that config file exists or throw exception
    if (!file_exists($path)) {
        throw new \Exception("Configuration file: [$path] cannot be found");
    }

    // php file
    if (preg_match('@^php$@i', $info['extension'])) {

      // keep it quiet and rethrow errors
      try {
        ob_start();
        $temp = require $path;
        ob_get_clean();
      } catch (\Exception $ex) {
        throw new Exception("PHP file threw an exception", 0, $ex);
      }

      // if we got a callable, run it and expect conf back
      if (is_callable($temp))
        $temp = call_user_func($temp);

      // we need an array, anything else, throw
      if (!$temp || !is_array($temp))
        throw new \Exception('PHP file does not return an array');

      $this->data = $temp;
      return;
    }

    // ini file
    if (preg_match('@^ini$@i', $info['extension'])) {
      $this->data = @parse_ini_file($path, true);
      if (!$this->data)
        throw new \Exception('INI parse error');
      return;
    }

    // json file
    if (preg_match('@^json$@i', $info['extension'])) {
      $this->data = json_decode(file_get_contents($path), true);
      if (json_last_error() !== JSON_ERROR_NONE)
        throw new \Exception('JSON parse error');
      return;
    }

    // unsupported type
    throw new \Exception('Unsupported configuration format');
  }

  /**
   * Gets a configuration setting using a simple or nested key.
   * Nested keys are similar to JSON paths that use the dot
   * dot notation.
   *
   * @param string $path config setting to fetch
   * @param mixed $default default value to use
   *
   * @return mixed config value, or null/default value if not found.
   */
  public function get($path, $default = null) {

    if (isset($this->cache[$path]))
      return $this->cache[$path];

    $segs = explode('.', $path);
    $root = $this->data;

    // nested case
    foreach ($segs as $part) {
      if (isset($root[$part])) {
        $root = $root[$part];
        continue;
      } else {
        $root = $default;
        break;
      }
    }

    // whatever we have is what we needed
    return ($this->cache[$path] = $root);
  }

  /**
   * Function for setting configuration values, using
   * either simple or nested keys.
   *
   * @param string $path config key to set
   * @param mixed $value value to use for the key
   *
   * @return void
   */
  public function set($path, $value) {

    $segs = explode('.', $path);
    $root = &$this->data;

    // crawl the path, creating nesting if needed
    while ($part = array_shift($segs)) {
      if (!isset($root[$part]) && count($segs))
        $root[$part] = array();
      $root = &$root[$part];
    }

    // assign value at target node
    $root = $value;

    // invalidate or create cache entry
    if ($root === null)
      unset($this->cache[$path]);
    else
      $this->cache[$path] = $root;
  }
}
?>
