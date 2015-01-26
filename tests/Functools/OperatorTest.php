<?php
namespace Teto\Functools;
use Teto\Functools as f;
use Teto\Functools\Operator as _;

final class OperatorTest extends \PHPUnit_Framework_TestCase
{
    public function test_operators_listed()
    {
        static $not_op = ['__construct', 'op', 'getInstance', 'initOperators'];

        $ref_class = new \ReflectionClass('Teto\Functools\Operator');
        $init_ops = $ref_class->getMethod('initOperators');
        $init_ops->setAccessible(true);
        $operators = $init_ops->invoke(null);
        $this->assertNotCount(0, $operators);
        $method_names = [];

        foreach ($ref_class->getMethods() as $method) {
            $name = str_replace('construct_', '', $method->getName());
            if (in_array($name, $not_op, true)) { continue; }
            if ($name[0] === '_') { $name = substr($name, 1); }

            $this->assertArrayHasKey($name, $operators);
            $this->assertEquals($method->getName(), $operators[$name]);
            $method_names[$method->getName()] = true;
        }

        foreach ($operators as $sym => $op) {
            $this->assertArrayHasKey($op, $method_names);
        }
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessageRegExp /\A'.+' is not exists.\z/
     */
    public function test_op_throws_error()
    {
        _::op("a\ta");
    }

    public function test_conditional()
    {
        $t = function($v) { return true;   };
        $f = function($v) { return false;  };
        $hoge = function($v) { return "Hoge"; };
        $fuga = function($v) { return "Fuga"; };

        $this->assertEquals("Hoge", _::conditional(true,  "Hoge", "Fuga"));
        $this->assertEquals("Fuga", _::conditional(false, "Hoge", "Fuga"));
        $this->assertEquals("Hoge", _::conditional_lazy($t, $hoge, $fuga, 1));
        $this->assertEquals("Fuga", _::conditional_lazy($f, $hoge, $fuga, 1));
    }

    public function test_method()
    {
        $now = time();

        $this->assertEquals(
            \DateTime::createFromFormat("Y-m-d", $now),
            call_user_func(_::method('createFromFormat', 'DateTime'), 'Y-m-d', $now)
        );
    }
    
    /**
     * @dataProvider dataProviderFor_range
     */
    public function test_range(
        $n, $lt_lt, $le_lt, $lt_le, $le_le, $gt_gt, $ge_gt, $gt_ge, $ge_ge
    ) {
        $this->assertSame($lt_lt, _::lt_and_lt(1, 2, $n));
        $this->assertSame($le_lt, _::le_and_lt(1, 2, $n));
        $this->assertSame($lt_le, _::lt_and_le(1, 2, $n));
        $this->assertSame($le_le, _::le_and_le(1, 2, $n));
        $this->assertSame($gt_gt, _::gt_and_gt(2, 1, $n));
        $this->assertSame($ge_gt, _::ge_and_gt(2, 1, $n));
        $this->assertSame($gt_ge, _::gt_and_ge(2, 1, $n));
        $this->assertSame($ge_ge, _::ge_and_ge(2, 1, $n));

        // 範囲指定が変な場合は常にfalse
        $this->assertFalse(_::le_and_le(2, 1, $n));
        $this->assertFalse(_::ge_and_ge(1, 2, $n));
    }

    public function dataProviderFor_range()
    {
        return [
            // n   lt_lt  le_lt  lt_le  le_le  gt_gt  ge_gt  gt_ge  ge_ge
            [0   , false, false, false, false, false, false, false, false],
            [1   , false,  true, false,  true, false, false,  true,  true],
            [1.5 ,  true,  true,  true,  true,  true,  true,  true,  true],
            [2   , false, false,  true,  true, false,  true, false,  true],
            [2.5 , false, false, false, false, false, false, false, false],
        ];
    }

    public function test_ski()
    {
        $s = _::op('s');
        $k = _::op('k');
        $i = _::op('i');

        $this->assertSame("teto", $i("teto"));
    }

    public function test_isset()
    {
        $ary = [0, "_true" => true, "_false" => false, "_null" => null];

        $this->assertTrue( _::construct_isset($ary, 0));
        $this->assertTrue( _::construct_isset($ary, "_true"));
        $this->assertTrue( _::construct_isset($ary, "_false"));
        $this->assertFalse(_::construct_isset($ary, "_null"));
        $this->assertFalse(_::construct_isset($ary, 1));
        $this->assertFalse(_::construct_isset($ary, "unknown"));

        $stdObj = new \stdClass;
        $stdObj->_false = false;
        $stdObj->_true  = true;
        $stdObj->_null  = null;

        $this->assertFalse(_::construct_isset($stdObj, 0));
        $this->assertTrue( _::construct_isset($stdObj, "_true"));
        $this->assertTrue( _::construct_isset($stdObj, "_false"));
        $this->assertFalse(_::construct_isset($stdObj, "_null"));
        $this->assertFalse(_::construct_isset($stdObj, 1));
        $this->assertFalse(_::construct_isset($stdObj, "unknown"));

        $tuple = f::tuple(true, false, "teto", null, "miko");

        $this->assertTrue( _::construct_isset($tuple, 0, '[]'));
        $this->assertTrue( _::construct_isset($tuple, 1, '[]'));
        $this->assertTrue( _::construct_isset($tuple, 2, '[]'));
        $this->assertFalse(_::construct_isset($tuple, 3, '[]'));
        $this->assertTrue( _::construct_isset($tuple, 4, '[]'));
        $this->assertFalse(_::construct_isset($tuple, 5, '[]'));
        $this->assertFalse(_::construct_isset($tuple, 6, '[]'));

        $this->assertTrue( _::index_isset(0, $tuple));
        $this->assertTrue( _::index_isset(1, $tuple));
        $this->assertTrue( _::index_isset(2, $tuple));
        $this->assertFalse(_::index_isset(3, $tuple));
        $this->assertTrue( _::index_isset(4, $tuple));
        $this->assertFalse(_::index_isset(5, $tuple));
        $this->assertFalse(_::index_isset(6, $tuple));
    }

    public function test_empty()
    {
        $ary = [0, "_true" => true, "_false" => false, "_null" => null];

        $this->assertTrue( _::construct_empty($ary, 0));
        $this->assertFalse(_::construct_empty($ary, "_true"));
        $this->assertTrue( _::construct_empty($ary, "_false"));
        $this->assertTrue( _::construct_empty($ary, "_null"));
        $this->assertTrue( _::construct_empty($ary, 1));
        $this->assertTrue( _::construct_empty($ary, "unknown"));

        $stdObj = new \stdClass;
        $stdObj->_false = false;
        $stdObj->_true  = true;
        $stdObj->_null  = null;

        $this->assertTrue( _::construct_empty($stdObj, 0));
        $this->assertFalse(_::construct_empty($stdObj, "_true"));
        $this->assertTrue( _::construct_empty($stdObj, "_false"));
        $this->assertTrue( _::construct_empty($stdObj, "_null"));
        $this->assertTrue( _::construct_empty($stdObj, 1));
        $this->assertTrue( _::construct_empty($stdObj, "unknown"));

        $tuple = f::tuple(true, false, "teto", null, "miko");
        $this->assertFalse(_::construct_empty($tuple, 0, '[]'));
        $this->assertTrue( _::construct_empty($tuple, 1, '[]'));
        $this->assertFalse(_::construct_empty($tuple, 2, '[]'));
        $this->assertTrue( _::construct_empty($tuple, 3, '[]'));
        $this->assertFalse(_::construct_empty($tuple, 4, '[]'));
        $this->assertTrue( _::construct_empty($tuple, 5, '[]'));
        $this->assertTrue( _::construct_empty($tuple, 6, '[]'));
    }

    public function test_array()
    {
        $this->assertEquals([],     _::construct_array());
        $this->assertEquals([1],    _::construct_array(1));
        $this->assertEquals([1, 2], _::construct_array(1, 2));
        $this->assertEquals(
            [[1], [2]],
            _::construct_array(_::construct_array(1), _::construct_array(2))
        );

        $this->assertEquals([3, 9], _::make_array_double(3, 9));
        $this->assertEquals([7, 5, 3], _::make_array_triple(7, 5, 3));
    }

    public function test_sort_functions()
    {
        $class = '\Teto\Functools\Operator';
        $sort_functions = [
            'sort', 'rsort', 'asort', 'arsort', 'ksort', 'krsort',
            'natsort', 'natcasesort',
        ];

        foreach ($sort_functions as $sort) {
            $source = [1 => 'hoge', "fuga", "piyo" => false];
            $copy   = $source;
            $actual = $sort($source);

            $this->assertEquals(
                $source,
                call_user_func([$class, $sort], $copy)
            );
        }

        $usort_functions = ['usort', 'uasort', 'uksort'];
        $compare_callback = function ($a, $b) { return 1; };

        foreach ($usort_functions as $usort) {
            $source = [1 => 'hoge', "fuga", "piyo" => false];
            $copy   = $source;
            $actual = $usort($source, $compare_callback);

            $this->assertEquals(
                $source,
                call_user_func([$class, $usort], $copy, $compare_callback)
            );
        }
    }

    public function test_tup_to_kv()
    {
        $method = ['Teto\Functools\Operator', 'tup_to_kv'];
        $expected = ["hoge" => "fuga", "fuga" => "piyo"];
        $actual = array_reduce([["hoge", "fuga"], ["fuga", "piyo"]], $method);
        
        $this->assertEquals($expected, $actual);
    }

    public function test_property()
    {
        $actual = new \stdClass;

        $this->assertSame($actual, _::property_assign('hoge', $actual, true));
        $this->assertSame(true, $actual->hoge);
        $this->assertSame(true, _::property_get('hoge', $actual));
        $this->assertTrue(_::property_isset('hoge', $actual));
        $this->assertSame($actual, _::property_unset('hoge', $actual, true));
        $this->assertFalse(_::property_isset('hoge', $actual));
    }

    public function test_index()
    {
        $source = [];

        $actual = _::index_assign('hoge', $source, true);
        $this->assertSame([], $source);
        $this->assertSame(['hoge' => true], $actual);
        $this->assertSame(true, _::index_get('hoge', $actual));
        $this->assertTrue(_::index_isset('hoge', $actual));

        $actual_unset = _::index_unset('hoge', $actual, true);
        $this->assertSame(['hoge' => true], $actual);
        $this->assertSame([], $actual_unset);
        $this->assertFalse(_::index_isset('hoge', $actual_unset));
    }

    public function test_functools_wrapper()
    {
        $f = function () {};
        $this->assertEquals(f::arity('explode'), _::arity('explode'));
        $this->assertEquals(f::tuple('hoge'),    _::tuple('hoge'));
        $this->assertEquals(f::cons("a", "b"),   _::cons("a", "b"));
        $this->assertEquals(f::curry($f),        _::curry($f));
        $this->assertEquals(f::fix($f),          _::fix($f, $f));
        $this->assertEquals(f::compose($f, $f),  _::compose($f, $f));
    }
}
