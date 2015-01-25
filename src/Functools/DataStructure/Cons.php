<?php
namespace Teto\Functools\DataStructure;

/**
 * Cons cell
 *
 * @package    Teto
 * @subpackage Functools\DataStructure
 * @author     USAMI Kenta <tadsan@zonu.me>
 * @copyright  2015 USAMI Kenta
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Cons implements \ArrayAccess, \Countable
{
    /** @var mixed */
    private $car;

    /** @var mixed */
    private $cdr;

    /**
     * @param mixed $car
     * @param mixed $cdr
     */
    public function __construct($car, $cdr)
    {
        $this->car = $car;
        $this->cdr = $cdr;
    }

    public function __get($offset) { return $this->$offset; }

    /** @return boolean */
    public function count($n = 0)
    {
        if ($this->car === null) { return $n; }
        if ($this->cdr === null) { return $n + 1; }
        if (!$this->cdr instanceof self) { return $n + 2; }

        return $this->cdr->count($n + 1);
    }

    /**
     * @param  int $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        if ($offset == 0) { return isset($this->car); }
        if ($offset == 1) { return isset($this->cdr[0]); }

        return isset($this->cdr[$offset - 1]);
    }

    /**
     * Lisp nth
     *
     * @param  int $offset
     * @return boolean
     */
    public function offsetGet($offset)
    {
        if ($offset === 0) { return $this->car; }
        if ($offset === 1) { return $this->cdr[0]; }

        return $this->cdr[$offset - 1];
    }

    /**
     * @param  mixed $_offset
     * @param  mixed $_value
     * @throws \OutOfRangeException
     */
    public function offsetSet($_offset, $_value)
    {
        throw new \OutOfRangeException;
    }

    public function offsetUnset($_offset) {}

    /**
     * Get by property
     *
     * @param  mixed $key
     * @param  mixed $default_val
     * @return mixed
     * @note   It is similar to `plist-get' of Lisp
     */
    public function pget($key, $default_val = null)
    {
        if ($this->car === $key) { return $this[1]; }
        if ($this->cdr === null) { return $default_val; }

        return $this->cdr->pget($key, $default_val);
    }

    /**
     * @param  mixed $key
     * @param  mixed $default_val
     * @return mixed
     * @note   It is similar to `assoc' of Lisp
     */
    public function assoc($key, $default_val = null)
    {
        if ($this->car->car === $key) { return $this->car; }
        if ($this->cdr === null) { return $default_val; }

        return $this->cdr->assoc($key, $default_val);
    }

    /**
     * @param  mixed $key
     * @param  mixed $default_val
     * @return mixed
     * @note   It is similar to `rassoc' of Lisp
     */
    public function rassoc($key, $default_val = null)
    {
        if ($this->car->cdr === $key) { return $this->car; }
        if ($this->cdr === null) { return $default_val; }

        return $this->cdr->rassoc($key, $default_val);
    }
}
