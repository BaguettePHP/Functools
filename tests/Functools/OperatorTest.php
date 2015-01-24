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
}
