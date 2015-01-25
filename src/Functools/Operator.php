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
    /** @var Operator */
    private static $singleton;

    /** @var array */
    private static $operators;

    private function __construct() {}

    /** @return Operator */
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

        $sym = preg_replace('/ *@ */', '@', $symbol);

        if (!isset(self::$operators[$sym])) {
            throw new \LogicException($symbol);
        }

        return [self::getInstance(), self::$operators[$sym]];
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

    /**
     * @param  mixed $begin
     * @param  mixed $ends
     * @param  mixed $val
     * @return boolean
     */
    public static function lt_and_lt($begin, $ends, $val)
    {
        return ($begin < $val) && ($val < $ends);
    }

    /**
     * @param  mixed $begin
     * @param  mixed $ends
     * @param  mixed $val
     * @return boolean
     */
    public static function le_and_lt($begin, $ends, $val)
    {
        return ($begin <= $val) && ($val < $ends);
    }

    /**
     * @param  mixed $begin
     * @param  mixed $ends
     * @param  mixed $val
     * @return boolean
     */
    public static function lt_and_le($begin, $ends, $val)
    {
        return ($begin < $val) && ($val <= $ends);
    }

    /**
     * @param  mixed $begin
     * @param  mixed $ends
     * @param  mixed $val
     * @return boolean
     */
    public static function le_and_le($begin, $ends, $val)
    {
        return ($begin <= $val) && ($val <= $ends);
    }

    /**
     * @param  mixed $begin
     * @param  mixed $ends
     * @param  mixed $val
     * @return boolean
     */
    public static function gt_and_gt($begin, $ends, $val)
    {
        return ($begin > $val) && ($val > $ends);
    }

    /**
     * @param  mixed $begin
     * @param  mixed $ends
     * @param  mixed $val
     * @return boolean
     */
    public static function ge_and_gt($begin, $ends, $val)
    {
        return ($begin >= $val) && ($val > $ends);
    }

    /**
     * @param  mixed $begin
     * @param  mixed $ends
     * @param  mixed $val
     * @return boolean
     */
    public static function gt_and_ge($begin, $ends, $val)
    {
        return ($begin > $val) && ($val >= $ends);
    }

    /**
     * @param  mixed $begin
     * @param  mixed $ends
     * @param  mixed $val
     * @return boolean
     */
    public static function ge_and_ge($begin, $ends, $val)
    {
        return ($begin >= $val) && ($val >= $ends);
    }

    // ===== Arithmetic Operators =====

    /**
     * @param  mixed $a
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
    public static function conditional_lazy($cond, $then, $else, $v)
    {
        return $cond($v) ? $then($v) : $else($v);
    }

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

    // ===== Array Helper =====

    /**
     * @param  mixed  $receiver
     * @param  string $method
     * @return array
     */
    public static function method($receiver, $method)
    {
        return [$receiver, $method];
    }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @return array
     */
    public static function make_array_double($a, $b)
    {
        return [$a, $b];
    }

    /**
     * @param  mixed $a
     * @param  mixed $b
     * @param  mixed $c
     * @return array
     */
    public static function make_array_triple($a, $b, $c)
    {
        return [$a, $b, $c];
    }

    // ===== Language Construct =====

    /**
     * @param  mixed $a
     */
    public static function construct_echo($a) { echo $a; }

    /**
     * @param  mixed $a
     * @return int
     */
    public static function construct_print($a) { return print $a; }

    /**
     * @param  mixed  $operand
     * @param  mixed  $idx
     * @param  string $access "[]"|"->"
     * @return boolean
     * @note This function does not use default argument.
     */
    public static function construct_isset($operand, $idx = null, $access = null) {
        $args = func_get_args();
        $operand = array_shift($args);

        if (!$args) { return isset($operand); }
        $idx = array_shift($args);
        if (is_array($operand)) { return isset($operand[$idx]); }
        $access = array_shift($args) ?: '->';

        return ($access === '[]')
            ? isset($operand[$idx])
            : isset($operand->$idx)
            ;
    }

    /**
     * @param  mixed  $operand
     * @param  mixed  $idx
     * @param  string $access "[]"|"->"
     * @return boolean
     * @note This function does not use default argument.
     */
    public static function construct_empty($operand, $idx = null, $access = null) {
        $args = func_get_args();
        $operand = array_shift($args);

        if (!$args) { return empty($operand); }
        $idx = array_shift($args);
        if (is_array($operand)) { return empty($operand[$idx]); }
        $access = array_shift($args) ?: '->';

        return ($access === '[]')
            ? empty($operand[$idx])
            : empty($operand->$idx)
            ;
    }

    /**
     * @param  mixed $a
     * @return mixed
     */
    public static function construct_require($a) { return require $a; }

    /**
     * @param  mixed $a
     * @return mixed
     */
    public static function construct_include($a) { return include $a; }

    /**
     * @param  mixed $a
     * @return mixed
     */
    public static function construct_require_once($a) { return require_once $a; }

    /**
     * @param  mixed $a
     * @return mixed
     */
    public static function construct_include_once($a) { return include_once $a; }

    /**
     * @param  mixed $a
     * @return mixed
     */
    public static function construct_eval($a) { return eval($a); }

    // ===== Index Access =====

    /**
     * @param  mixed $idx
     * @param  mixed $a
     * @return mixed
     */
    public static function index_get($idx, $a)
    {
        return $a[$idx];
    }

    /**
     * @param  mixed $idx
     * @param  mixed $a
     * @param  mixed $v
     * @return mixed
     */
    public static function index_assign($idx, $a, $v)
    {
        $a[$idx] = $v;
        return $v;
    }

    /**
     * @param  mixed $idx
     * @param  mixed $a
     * @return boolean
     */
    public static function index_isset($idx, $a)
    {
        return isset($a[$idx]);
    }

    /**
     * @param  mixed $idx
     * @param  mixed $a
     * @return mixed
     */
    public static function index_unset($idx, $a)
    {
        unset($a[$idx]);

        return $a;
    }

    // ===== Property Access =====

    /**
     * @param  mixed $idx
     * @param  mixed $a
     * @return mixed
     */
    public static function property_get($idx, $a)
    {
        return $a->$idx;
    }

    /**
     * @param  mixed $idx
     * @param  mixed $a
     * @param  mixed $v
     * @return mixed
     */
    public static function property_assign($idx, $a, $v)
    {
        $a->$idx = $v;

        return $v;
    }

    /**
     * @param  mixed $idx
     * @param  mixed $a
     * @return boolean
     */
    public static function property_isset($idx, $a)
    {
        return isset($a->$idx);
    }

    /**
     * @param  mixed $idx
     * @param  mixed $a
     * @return mixed
     */
    public static function property_unset($idx, $a)
    {
        unset($a->$idx);

        return $a;
    }

    // ===== Sort functions wrapper =====

    public static function sort($array, $sort_flags = SORT_REGULAR)
    {
        sort($array, $sort_flags);

        return $array;
    }

    public static function rsort($array, $sort_flags = SORT_REGULAR)
    {
        rsort($array, $sort_flags);

        return $array;
    }

    public static function asort($array, $sort_flags = SORT_REGULAR)
    {
        asort($array, $sort_flags);

        return $array;
    }

    public static function arsort($array, $sort_flags = SORT_REGULAR)
    {
        arsort($array, $sort_flags);

        return $array;
    }

    public static function ksort($array, $sort_flags = SORT_REGULAR)
    {
        ksort($array, $sort_flags);

        return $array;
    }

    public static function krsort($array, $sort_flags = SORT_REGULAR)
    {
        krsort($array, $sort_flags);

        return $array;
    }

    public static function usort($array, callable $value_compare_func)
    {
        usort($array, $value_compare_func);

        return $array;
    }

    public static function uasort($array, callable $value_compare_func)
    {
        uasort($array, $value_compare_func);

        return $array;
    }

    public static function uksort($array, callable $key_compare_func)
    {
        uksort($array, $key_compare_func);

        return $array;
    }

    public static function natsort($array)
    {
        natsort($array);

        return $array;
    }

    public static function natcasesort($array)
    {
        natcasesort($array);

        return $array;
    }


    // ===== Utility =====

    /**
     * @param  mixed $acc
     * @param  array $tup
     * @return array
     */
    public static function tup_to_kv($acc, array $tup)
    {
        $key = array_shift($tup);
        $acc[$key] = (count($tup) !== 1) ? $tup : array_shift($tup);

        return $acc;
    }

    // ===== Functools Functions =====

    public static function arity()
    {
        return call_user_func_array('\Teto\Functools::arity', func_get_args());
    }

    public static function curry()
    {
        return call_user_func_array('\Teto\Functools::curry', func_get_args());
    }

    public static function compose()
    {
        return call_user_func_array('\Teto\Functools::compose', func_get_args());
    }

    public static function cons()
    {
        return call_user_func_array('\Teto\Functools::cons', func_get_args());
    }

    public static function tuple()
    {
        return call_user_func_array('\Teto\Functools::tuple', func_get_args());
    }

    public static function fix($f)
    {
        return call_user_func_array('\Teto\Functools::fix', func_get_args());
    }


    /** S combinator */
    public static function s($f, $g, $x) { return call_user_func($f($x), $g($x)); }

    /** K combinator */
    public static function k($x, $y) { return $x; }

    /** I combinator */
    public static function i($a)
    {
        $_ = self::getInstance();
        $s = [$_, 's']; /** @var callable $s */
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
            '<@<'   => 'lt_and_lt',
            '<=@<'  => 'le_and_lt',
            '<@<='  => 'lt_and_le',
            '<=@<=' => 'le_and_le',
            '>@>'   => 'gt_and_gt',
            '>=@>'  => 'ge_and_gt',
            '>@>='  => 'gt_and_ge',
            '>=@>=' => 'ge_and_ge',
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
            '.'   => 'concatenation',
            '@[]'  => 'index_get',
            '@[]=' => 'index_assign',
            '[]='  => 'index_assign',
            'isset[]' => 'index_isset',
            'unset[]' => 'index_unset',
            '@->'  => 'property_get',
            '@->=' => 'property_assign',
            '->='  => 'property_assign',
            'isset->' => 'property_isset',
            'unset->' => 'property_unset',
            'if'  => 'conditional_lazy',
            '[,]' => 'make_array_double',
            '[,,]' => 'make_array_triple',

            'id' => 'id',
            'equal' => 'equal',
            'identical' => 'identical',
            'not_equal' => 'not_equal',
            'not_identical' => 'not_identical',
            'less_than' => 'less_than',
            'greater_than' => 'greater_than',
            'less_than_or_equal_to' => 'less_than_or_equal_to',
            'greater_than_or_equal_to' => 'greater_than_or_equal_to',
            'lt_and_lt' => 'lt_and_lt',
            'le_and_lt' => 'le_and_lt',
            'lt_and_le' => 'lt_and_le',
            'le_and_le' => 'le_and_le',
            'gt_and_gt' => 'gt_and_gt',
            'ge_and_gt' => 'ge_and_gt',
            'gt_and_ge' => 'gt_and_ge',
            'ge_and_ge' => 'ge_and_ge',
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
            'method' => 'method',
            'make_array_double' => 'make_array_double',
            'make_array_triple' => 'make_array_triple',
            'echo'  => 'construct_echo',
            'print' => 'construct_print',
            'isset' => 'construct_isset',
            'empty' => 'construct_empty',
            'eval'  => 'construct_eval',
            'require' => 'construct_require',
            'include' => 'construct_include',
            'require_once' => 'construct_require_once',
            'include_once' => 'construct_include_once',
            'index_get'    => 'index_get',
            'index_assign' => 'index_assign',
            'index_isset'  => 'index_isset',
            'index_unset'  => 'index_unset',
            'property_get' => 'property_get',
            'property_assign' => 'property_assign',
            'property_isset'  => 'property_isset',
            'property_unset'  => 'property_unset',
            'sort'    => 'sort',
            'rsort'   => 'rsort',
            'asort'   => 'asort',
            'arsort'  => 'arsort',
            'ksort'   => 'ksort',
            'krsort'  => 'krsort',
            'usort'   => 'usort',
            'uasort'  => 'uasort',
            'uksort'  => 'uksort',
            'natsort' => 'natsort',
            'natcasesort' => 'natcasesort',

            'tup_to_kv' => 'tup_to_kv',
            'arity' => 'arity',
            'compose' => 'compose',
            'curry' => 'curry',
            'cons' => 'cons',
            'tuple' => 'tuple',
            's' => 's',
            'k' => 'k',
            'i' => 'i',
            'skk' => 'i',
            'fix' => 'fix',
            'z' => 'fix',
        ];

        return self::$operators;
    }
}
