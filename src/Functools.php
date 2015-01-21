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
    public static function partial(
        callable $callable,
        array $arguments = [],
        $pos = null
    ) {
        return new Functools\PartialCallable($callable, $arguments, $pos);
    }
}
