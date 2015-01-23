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
    private $callable;
    /** @var mixed[] */
    private $arguments;
    /** @var int|null */
    private $pos;

    public function __construct(callable $callable, array $arguments = [], $pos = null)
    {
        $this->callable  = $callable;
        $this->arguments = $arguments;
        $this->pos       = $pos;
    }
    
    public function __invoke()
    {
        return call_user_func_array($this->callable, $this->arguments(func_get_args()));
    }

    /**
     * Make partially applied function
     *
     * @note Returns new instance
     */
    public function partial(array $arguments)
    {
        return new PartialCallable($this->callable, $this->arguments + $arguments, $this->pos);
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
