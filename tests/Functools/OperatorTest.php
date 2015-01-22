<?php
namespace Teto\Functools;
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
}
