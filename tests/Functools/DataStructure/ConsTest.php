<?php
namespace Teto\Functools\DataStructure;

final class ConsTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $triple = new Cons("foo", new Cons("bar", new Cons("buz", null)));

        $this->assertSame("foo", $triple[0]);
        $this->assertSame("bar", $triple[1]);
        $this->assertSame("buz", $triple[2]);
        $this->assertSame(null,  $triple[3]);

        $this->assertTrue( isset($triple[0]));
        $this->assertTrue( isset($triple[1]));
        $this->assertTrue( isset($triple[2]));
        $this->assertFalse(isset($triple[3]));

        $this->assertSame(3, count($triple));
        $this->assertSame(2, count($triple->cdr));
        $this->assertSame(1, count($triple->cdr->cdr));
    }
}
