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

    public function test_pget()
    {
        $plist = new Cons(":name", new Cons("Teto Kasane", new Cons(":age", new Cons(31, null))));
        $undefined = "'undefined";

        $this->assertSame("Teto Kasane", $plist->pget(":name",   $undefined));
        $this->assertSame(31,            $plist->pget(":age",    $undefined));
        $this->assertSame($undefined,    $plist->pget(":author", $undefined));
    }
}
