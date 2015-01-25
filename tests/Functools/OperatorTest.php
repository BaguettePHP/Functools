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
    }
}
