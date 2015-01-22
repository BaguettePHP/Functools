<?php
namespace Teto\Functools;
use Teto\Functools as f;

/**
 * Object an abstraction of the operator expression
 *
 * @package    Teto
 * @subpackage Functools
 * @author     USAMI Kenta <tadsan@zonu.me>
 * @copyright  2015 USAMI Kenta
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Operator
{
    /** @var BinaryOperator */
    private static $singleton;

    /** @var array */
    private static $operators;

    private function __construct() {}

    /** @return BinaryOperator */
    private static function getInstance()
    {
        if (!self::$singleton) { self::$singleton = new self; }

        return self::$singleton;
    }

    /**
     * @param  string   $symbol
     * @return callable
     */
    public static function op($symbol)
    {
        if (!self::$operators) { self::initOperators(); }

        if (!isset(self::$operators[$symbol])) {
            throw new \LogicException;
        }

        return [self::getInstance(), self::$operators[$symbol]];
    }

    /**
     * @param  mixed $a
     * @return mixed
     */
    public static function id($a) { return $a; }

    // ===== Comparison Operators =====

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function equal($a, $b) { return $a == $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function identical($a, $b) { return $a === $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function not_equal($a, $b) { return $a != $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function not_identical($a, $b) { return $a !== $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function less_than($a, $b) { return $a < $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function greater_than($a, $b) { return $a > $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function less_than_or_equal_to($a, $b) { return $a <= $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function greater_than_or_equal_to($a, $b) { return $a >= $b; }

    // ===== Arithmetic Operators =====

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return int|float
     */
    public static function negation($a) { return -$a; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return int|float
     */
    public static function addition($a, $b) { return $a + $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return int|float
     */
    public static function subtraction($a, $b) { return $a - $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return int|float
     */
    public static function multiplication($a, $b) { return $a * $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return int|float
     */
    public static function division($a, $b) { return $a / $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return int|float
     */
    public static function modulus($a, $b) { return $a % $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return int|float
     */
    public static function exponentiation($a, $b) { return pow($a, $b); }

    // ===== Bitwise Operators =====

    /**
     * @param  int $a
     * @param  int $b
     * @return int
     */
    public static function bit_and($a, $b) { return $a & $b; }

    /**
     * @param  int $a
     * @param  int $b
     * @return int
     */
    public static function bit_or($a, $b) { return $a | $b; }

    /**
     * @param  int $a
     * @param  int $b
     * @return int
     */
    public static function bit_xor($a, $b) { return $a ^ $b; }

    /**
     * @param  int $a
     * @return int
     */
    public static function bit_not($a) { return ~$a; }

    /**
     * @param  int $a
     * @return int
     */
    public static function bit_shift_left($a, $b) { return $a << $b; }

    /**
     * @param  int $a
     * @return int
     */
    public static function bit_shift_right($a, $b) { return $a >> $b; }

    // ===== Logical Operators =====

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function logical_and($a, $b) { return $a and $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function logical_or($a, $b) { return $a or $b; }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return boolean
     */
    public static function logical_xor($a, $b) { return $a xor $b; }

    /**
     * @param  mixed $a
     * @return boolean
     */
    public static function logical_not($a) { return !$a; }

    // ===== Ternary Operator =====

    /**
     * @param  mixed $cond
     * @param  mixed $then
     * @param  mixed $else
     * @return mixed
     */
    public static function conditional($cond, $then, $else) { return $cond ? $then : $else; }

    /**
     * @param  callable $cond
     * @param  callable $then
     * @param  callable $else
     * @return mixed
     *
     * @link http://en.wikipedia.org/wiki/Thunk This technique is known as `thunk'.
     */
    public static function conditional_lazy($cond, $then, $else) { return $cond() ? $then() : $else(); }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return mixed
     */
    public static function elvis($a, $b) { return $a ?: $b; }

    // ===== String Operators =====

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return string
     */
    public static function concatenation($a, $b) { return $a . $b; }

    // ===== Type Operators =====

    /**
     * @param  mixed  $a
     * @param  string $b
     * @return boolean
     */
    public static function _instanceof($a, $b) { return $a instanceof $b; }

    // ===== Language Construct =====

    /**
     * @param  mixed $a
     */
    public static function construct_echo($a) { echo $a; }

    /**
     * @param  mixed $a
     */
    public static function construct_print($a) { return print $a; }

    /**
     * @param  mixed $a
     */
    public static function construct_require($a) { return require $a; }

    /**
     * @param  mixed $a
     */
    public static function construct_include($a) { return include $a; }

    /**
     * @param  mixed $a
     */
    public static function construct_require_once($a) { return require_once $a; }

    /**
     * @param  mixed $a
     */
    public static function construct_include_once($a) { return include_once $a; }

    /**
     * @param  mixed $a
     * @return mixed
     */
    public static function construct_eval($a) { return eval($a); }

    /** S combinator */
    public static function s($f, $g, $x) { return call_user_func($f($x), $g($x)); }

    /** K combinator */
    public static function k($x, $y) { return $x; }

    /** I combinator */
    public static function i($a) {
        $_ = self::getInstance();
        $s = [$_, 's'];
        $k = f::curry([$_, 'k']);

        return $s($k, $k, $a);
    }

    private static function initOperators()
    {
        self::$operators = [
            '=='  => 'equal',
            '===' => 'identical',
            '!='  => 'not_equal',
            '<>'  => 'not_equal',
            '!==' => 'not_identical',
            '<'   => 'less_than',
            '>'   => 'greater_than',
            '<='  => 'less_than_or_equal_to',
            '>='  => 'greater_than_or_equal_to',
            'lt'  => 'less_than',
            'gt'  => 'greater_than',
            'le'  => 'less_than_or_equal_to',
            'ge'  => 'greater_than_or_equal_to',
            '-@'  => 'negation',
            '+'   => 'addition',
            '-'   => 'subtraction',
            '*'   => 'multiplication',
            '/'   => 'division',
            '%'   => 'modulus',
            '**'  => 'exponentiation',
            'add' => 'addition',
            'sub' => 'subtraction',
            'mul' => 'multiplication',
            'div' => 'division',
            'mod' => 'modulus',
            'pow' => 'exponentiation',
            '&'   => 'bit_and',
            '|'   => 'bit_or',
            '^'   => 'bit_xor',
            '~@'  => 'bit_not',
            '<<'  => 'bit_shift_left',
            '>>'  => 'bit_shift_right',
            '&&'  => 'logical_and',
            '||'  => 'logical_or',
            'xor' => 'logical_xor',
            '!@'  => 'logical_not',
            '!'   => 'logical_not',
            '?:'  => 'elvis',

            'id' => 'id',
            'equal' => 'equal',
            'identical' => 'identical',
            'not_equal' => 'not_equal',
            'not_identical' => 'not_identical',
            'less_than' => 'less_than',
            'greater_than' => 'greater_than',
            'less_than_or_equal_to' => 'less_than_or_equal_to',
            'greater_than_or_equal_to' => 'greater_than_or_equal_to',
            'negation' => 'negation',
            'addition' => 'addition',
            'subtraction' => 'subtraction',
            'multiplication' => 'multiplication',
            'division' => 'division',
            'modulus' => 'modulus',
            'exponentiation' => 'exponentiation',
            'bit_and' => 'bit_and',
            'bit_or' => 'bit_or',
            'bit_xor' => 'bit_xor',
            'bit_not' => 'bit_not',
            'bit_shift_left' => 'bit_shift_left',
            'bit_shift_right' => 'bit_shift_right',
            'logical_and' => 'logical_and',
            'logical_or' => 'logical_or',
            'logical_xor' => 'logical_xor',
            'logical_not' => 'logical_not',
            'conditional' => 'conditional',
            'conditional_lazy' => 'conditional_lazy',
            'elvis' => 'elvis',
            'concatenation' => 'concatenation',
            'instanceof' => '_instanceof',
            'echo'  => 'construct_echo',
            'print' => 'construct_print',
            'eval'  => 'construct_eval',
            'echo'  => 'construct_echo',
            'print' => 'construct_print',
            'eval'  => 'construct_eval',
            'require' => 'construct_require',
            'include' => 'construct_include',
            'require_once' => 'construct_require_once',
            'include_once' => 'construct_include_once',
            's' => 's',
            'k' => 'k',
            'i' => 'i',
            'skk' => 'i',
        ];

        return self::$operators;
    }
}
