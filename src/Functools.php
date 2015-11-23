<?php
namespace Teto;

/**
 * Functional toolbox
 *
 * @package    Teto
 * @author     USAMI Kenta <tadsan@zonu.me>
 * @copyright  2015 USAMI Kenta
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Functools
{
    private function __construct() {}

    /**
     * Partial application
     *
     * @param  callable $callback
     * @param  mixed[]  $arguments
     * @param  int      $pos
     * @return \Teto\Functools\PartialCallable
     */
    public static function partial(callable $callback, array $arguments = [], $pos = null)
    {
        return new Functools\PartialCallable($callback, $arguments, $pos);
    }

    /**
     * Returns an indication of the number of arguments accepted by a callable.
     *
     * @param  callable $callback
     * @return int
     */
    public static function arity (callable $callback)
    {
        if (is_array($callback) ||
            (is_string($callback) && strpos($callback, '::') !== false)) {
            list($class, $method) = is_string($callback) ? explode('::', $callback) : $callback;
            $reflection = (new \ReflectionClass($class))->getMethod($method);
        } elseif (is_object($callback) && !($callback instanceof \Closure)) {
            $reflection = (new \ReflectionClass($callback))->getMethod('__invoke');
        } else {
            $reflection = new \ReflectionFunction($callback);
        }

        return $reflection->getNumberOfParameters();
    }

    /**
     * Make curried callable object
     *
     * @param  callable $callback
     * @return \Teto\Functools\CurriedCallable
     */
    public static function curry(callable $callback)
    {
        return new Functools\CurriedCallable($callback, self::arity($callback), []);
    }

    /**
     * Call function of Functools\Operator
     *
     * @param  string   $symbol
     * @param  mixed[]  $arguments
     * @return mixed
     */
    public static function apply($symbol, array $arguments = [])
    {
        $op = Functools\Operator::op($symbol);

        return call_user_func_array($op, $arguments);
    }

    /**
     * Get callable object from Functools\Operator (and partial application)
     *
     * @param  string   $symbol
     * @param  mixed[]  $arguments
     * @param  int      $pos
     * @return callable
     * @see \Teto\Functools\Operator::op()
     */
    public static function op($symbol, array $arguments = [], $pos = null)
    {
        $op = Functools\Operator::op($symbol);

        return $arguments ? self::partial($op, $arguments, $pos) : $op;
    }

    /**
     * Compose functions
     *
     * @param  callable $f
     * @param  callable $g,...
     * @return callable
     */
    public static function compose(callable $f, callable $g)
    {
        $fs = func_get_args();
        /** @var callable $f */
        $f  = array_shift($fs);
        /** @var callable $g */
        $g = (count($fs) === 1)
           ? array_shift($fs) : call_user_func_array('self::compose', $fs);

        return function ($a) use ($f, $g) {
            return $g($f($a));
        };
    }

    /**
     * Make new cons cell
     *
     * @param  mixed $car
     * @param  mixed $cdr
     * @return \Teto\Functools\DataStructure\Cons
     */
    public static function cons($car, $cdr)
    {
        return new Functools\DataStructure\Cons($car, $cdr);
    }

    /**
     * Make n-tuple
     *
     * @param  mixed $item,...
     * @return \Teto\Functools\DataStructure\Cons
     */
    public static function tuple($item)
    {
        $items = func_get_args();
        $car = array_shift($items);

        return new Functools\DataStructure\Cons(
            $car,
            $items ? call_user_func_array('self::tuple', $items) : null
        );
    }

    /**
     * Fixed point operator (Z combinator)
     *
     * @param  callable $f
     * @return callable
     */
    public static function fix(callable $f)
    {
        return call_user_func(
            function (callable $x) use ($f) {
                return $f(
                    function () use ($f, $x) {
                        return call_user_func_array($x($x), func_get_args());
                    }
                );
            },
            function (callable $x) use ($f) {
                return $f(
                    function () use ($f, $x) {
                        return call_user_func_array($x($x), func_get_args());
                    }
                );
            }
        );
    }

    /**
     * Function Memoizer
     *
     * @param  callable $f memoize function
     * @return callable
     */
    public static function memoize(callable $f, array $cache = [])
    {
        return function () use ($f, &$cache) {
            $args = func_get_args();

            if (empty($args)) {
                return call_user_func_array($f, $args);
            }

            if (count($args) === 1 && is_scalar($args[0])) {
                $idx = $args[0];
            } else {
                $idx = serialize($args);
            }

            if (!isset($cache[$idx])) {
                $cache[$idx] = call_user_func_array($f, $args);
            }

            return $cache[$idx];
        };
    }
}
