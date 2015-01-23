<?php
namespace Teto;
use Teto\Functools as f;
use Teto\Functools\DataStructure\Cons;

final class FunctoolsTest extends \PHPUnit_Framework_TestCase
{
    public function test_partial()
    {
        $actual = Functools::partial("explode", [0 => ":", 2 => 2], 1);

        $this->assertInstanceOf('Teto\Functools\PartialCallable', $actual);
        $this->assertEquals(["a"],        $actual("a"));
        $this->assertEquals(["a", "b"],   $actual("a:b"));
        $this->assertEquals(["a", "b:c"], $actual("a:b:c"));
    }

    public function test_arity()
    {
        $this->assertEquals(3, Functools::arity(function ($a, $b, $c) { }));
    }

    public function test_curry()
    {
        $actual = Functools::curry(function ($a, $b, $c) { return $a.$b.$c; });
        $this->assertInstanceOf('Teto\Functools\CurriedCallable', $actual);
        $this->assertEquals(1, Functools::arity($actual));

        $actual_a = $actual("A");
        $this->assertInstanceOf('Teto\Functools\CurriedCallable', $actual_a);
        $this->assertEquals(1, Functools::arity($actual_a));

        $actual_ab = $actual_a("b");
        $this->assertInstanceOf('Teto\Functools\CurriedCallable', $actual_ab);
        $this->assertEquals(1, Functools::arity($actual_ab));

        $actual_abc = $actual_ab("C");
        $this->assertSame("AbC", $actual_abc);
        $this->assertNotInstanceOf('Teto\Functools\CurriedCallable', $actual_abc);
    }

    public function test_compose()
    {
        $expected = ["2", "4", "6", "8", "10"];

        $x2 = f::op('*', [2]);
        $x2_str = f::compose($x2, "strval");
        $actual = array_map($x2_str, range(1, 5));

        $this->assertContainsOnly('string', $actual);
        $this->assertSame($expected, $actual);
    }

    public function test_tuple()
    {
        $expected = new Cons("foo", new Cons("bar", new Cons("buz", null)));
        $actual = f::tuple("foo", "bar", "buz");
    }

    public function test_fix()
    {
        $actual = Functools::fix(function ($rec) {
            return function ($n) use ($rec) {
                return ($n == 0) ? 1 : $n * $rec($n - 1);
            };
        });

        $this->assertEquals(120, $actual(5));
    }
}
