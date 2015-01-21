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
}
