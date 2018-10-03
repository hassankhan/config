<?php

namespace Noodlehaus;

use ArrayAccess;
use Iterator;

/**
 * Abstract Config class
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
abstract class AbstractConfig implements ArrayAccess, ConfigInterface, Iterator
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
    protected $cache = [];

    /**
     * Constructor method and sets default options, if any
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = array_merge($this->getDefaults(), $data);
    }

    /**
     * Override this method in your own subclass to provide an array of default
     * options and values
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    protected function getDefaults()
    {
        return [];
    }

    /**
     * ConfigInterface Methods
     */

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->cache[$key];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $segs = explode('.', $key);
        $root = &$this->data;
        $cacheKey = '';

        // Look for the key, creating nested keys if needed
        while ($part = array_shift($segs)) {
            if ($cacheKey != '') {
                $cacheKey .= '.';
            }
            $cacheKey .= $part;
            if (!isset($root[$part]) && count($segs)) {
                $root[$part] = [];
            }
            $root = &$root[$part];

            //Unset all old nested cache
            if (isset($this->cache[$cacheKey])) {
                unset($this->cache[$cacheKey]);
            }

            //Unset all old nested cache in case of array
            if (count($segs) == 0) {
                foreach ($this->cache as $cacheLocalKey => $cacheValue) {
                    if (substr($cacheLocalKey, 0, strlen($cacheKey)) === $cacheKey) {
                        unset($this->cache[$cacheLocalKey]);
                    }
                }
            }
        }

        // Assign value at target node
        $this->cache[$key] = $root = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        // Check if already cached
        if (isset($this->cache[$key])) {
            return true;
        }

        $segments = explode('.', $key);
        $root = $this->data;

        // nested case
        foreach ($segments as $segment) {
            if (array_key_exists($segment, $root)) {
                $root = $root[$segment];
                continue;
            } else {
                return false;
            }
        }

        // Set cache for the given key
        $this->cache[$key] = $root;

        return true;
    }

    /**
     * Merge config from another instance
     *
     * @param ConfigInterface $config
     * @return ConfigInterface
     */
    public function merge(ConfigInterface $config)
    {
        $this->data = array_replace_recursive($this->data, $config->all());
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * ArrayAccess Methods
     */

    /**
     * Gets a value using the offset as a key
     *
     * @param  string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Checks if a key exists
     *
     * @param  string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Sets a value using the offset as a key
     *
     * @param  string $offset
     * @param  mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Deletes a key and its value
     *
     * @param  string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->set($offset, null);
    }

    /**
     * Iterator Methods
     */

    /**
     * Returns the data array element referenced by its internal cursor
     *
     * @return mixed The element referenced by the data array's internal cursor.
     *     If the array is empty or there is no element at the cursor, the
     *     function returns false. If the array is undefined, the function
     *     returns null
     */
    public function current()
    {
        return (is_array($this->data) ? current($this->data) : null);
    }

    /**
     * Returns the data array index referenced by its internal cursor
     *
     * @return mixed The index referenced by the data array's internal cursor.
     *     If the array is empty or undefined or there is no element at the
     *     cursor, the function returns null
     */
    public function key()
    {
        return (is_array($this->data) ? key($this->data) : null);
    }

    /**
     * Moves the data array's internal cursor forward one element
     *
     * @return mixed The element referenced by the data array's internal cursor
     *     after the move is completed. If there are no more elements in the
     *     array after the move, the function returns false. If the data array
     *     is undefined, the function returns null
     */
    public function next()
    {
        return (is_array($this->data) ? next($this->data) : null);
    }

    /**
     * Moves the data array's internal cursor to the first element
     *
     * @return mixed The element referenced by the data array's internal cursor
     *     after the move is completed. If the data array is empty, the function
     *     returns false. If the data array is undefined, the function returns
     *     null
     */
    public function rewind()
    {
        return (is_array($this->data) ? reset($this->data) : null);
    }

    /**
     * Tests whether the iterator's current index is valid
     *
     * @return bool True if the current index is valid; false otherwise
     */
    public function valid()
    {
        return (is_array($this->data) ? key($this->data) !== null : false);
    }

    /**
     * Remove a value using the offset as a key
     *
     * @param  string $key
     *
     * @return void
     */
    public function remove($key)
    {
        $this->offsetUnset($key);
    }
}
