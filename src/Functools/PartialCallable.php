<?php
namespace Teto\Functools;

/**
 * array_map compatible partial application wrapper
 *
 * @package    Teto
 * @subpackage Functools
 * @author     USAMI Kenta <tadsan@zonu.me>
 * @copyright  2015 USAMI Kenta
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class PartialCallable
{
    /** @var callable */
    private $callback;
    /** @var mixed[] */
    private $arguments;
    /** @var int|null */
    private $pos;

    /**
     * @param callable $callback
     * @param mixed[]  $arguments
     * @param int      null
     */
    public function __construct(callable $callback, array $arguments = [], $pos = null)
    {
        $this->callback  = $callback;
        $this->arguments = $arguments;
        $this->pos       = $pos;
    }
    
    /**
     * @param  mixed $arg,...
     * @return mixed
     */
    public function __invoke($arg = null)
    {
        return call_user_func_array($this->callback, $this->arguments(func_get_args()));
    }

    /**
     * Make partially applied function
     *
     * @param  mixed[] $arguments
     * @param  int     null
     * @return PartialCallable Returns new instance
     */
    public function partial(array $arguments, $pos = null)
    {
        return new PartialCallable($this->callback, $this->arguments + $arguments, ($pos !== null) ? $pos : $this->pos);
    }

    private function arguments(array $additional_arguments)
    {
        $arguments = $this->arguments;

        if ($this->pos < 0) {
            // through
        }elseif ($this->pos !== null) {
            $arguments[$this->pos] = array_shift($additional_arguments);
            ksort($arguments);
        } else {
        	$arguments = array_merge($arguments, $additional_arguments);
        }
        
        return $arguments;
    }
}
