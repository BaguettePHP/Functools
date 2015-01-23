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
     * @return PartialCallable
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
     * @return Functools\CurriedCallable
     */
    public static function curry(callable $callback)
    {
        return new Functools\CurriedCallable($callback, self::arity($callback), []);
    }

    public static function op($symbol, array $arguments = [], $pos = null)
    {
        $op = Functools\Operator::op($symbol);

        return $arguments ? self::partial($op, $arguments, $pos) : $op;
    }

    /**
     * @param  callable $item,...
     * @return Functools\DataStructure\Cons
     */
    public static function compose()
    {
        $fs = func_get_args();
        $f  = array_shift($fs);

        if (empty($f) || empty($fs)) {
            throw new \LogicException();
        }

        $g = (count($fs) === 1) ? array_shift($fs) : call_user_func_array('self::compose', $fs);

        return function ($a) use ($f, $g) {
            return $g($f($a));
        };
    }

    /**
     * @param  mixed $item,...
     * @return Functools\DataStructure\Cons
     */
    public static function tuple()
    {
        $items = func_get_args();
        $car = array_shift($items);

        return new Functools\DataStructure\Cons(
            $car,
            $items ? call_user_func_array('self::tuple', $items) : null
        );
    }
}
