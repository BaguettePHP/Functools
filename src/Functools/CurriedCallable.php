<?php
namespace Teto\Functools;
use Teto\Functools as f;

/**
 * array_map compatible partial application wrapper
 *
 * @package    Teto
 * @subpackage Functools
 * @author     USAMI Kenta <tadsan@zonu.me>
 * @copyright  2015 USAMI Kenta
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class CurriedCallable
{
    /** @var callable */
    private $callable;
    /** @var int */
    private $arity;
    /** @var mixed[] */
    private $arguments;

    public function __construct(callable $callable, $arity, array $arguments)
    {
        $this->callable  = $callable;
        $this->arity     = $arity;
        $this->arguments = $arguments;
    }
    
    public function __invoke($arg)
    {
        $args = $this->arguments;
        $args[] = $arg;

        if (count($args) < $this->arity) {
            return new self($this->callable, $this->arity, $args);
        }

        return call_user_func_array($this->callable, $args);
    }
}
