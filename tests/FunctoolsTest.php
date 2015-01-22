<?php
namespace Teto;

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
}
