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

    /**
     * @expectedException \OutOfRangeException
     */
    public function test_set_unset()
    {
        $tuple = new Cons("foo", null);

        unset($tuple["foo"]);
        $this->assertEquals("foo", $tuple[0]);

        $tuple[0] = "hoge";
    }

    public function test_offset()
    {
        $list = new Cons("apple", new Cons("banana", new Cons("orange", null)));

        $this->assertEquals("apple",  $list[0]);
        $this->assertEquals("banana", $list[1]);
        $this->assertEquals("orange", $list[2]);
        $this->assertNull($list[3]);
    }

    /**
     * @expectedException \OutOfRangeException
     * @dataProvider dataProviderFor_test_offset_raiseException
     */
    public function test_offset_raiseException($offset)
    {
        $list = new Cons("apple", new Cons("banana", new Cons("orange", null)));
        $this->assertFalse(isset($list[$offset]));

        $_ = $list[$offset];
    }

    public function dataProviderFor_test_offset_raiseException()
    {
        return [
            ['offset' =>    -1],
            ['offset' =>   2.0],
            ['offset' =>  -100],
            ['offset' => "3.0"],
            ['offset' =>     9],
        ];
    }


    public function test_pget()
    {
        $plist = new Cons(":name", new Cons("Teto Kasane", new Cons(":age", new Cons(31, null))));
        $undefined = "'undefined";

        $this->assertSame("Teto Kasane", $plist->pget(":name",   $undefined));
        $this->assertSame(31,            $plist->pget(":age",    $undefined));
        $this->assertSame($undefined,    $plist->pget(":author", $undefined));
    }

    public function test_assoc()
    {
        $name = new Cons(":name", "Teto Kasane");
        $age  = new Cons(":age", 31);
        $alist = new Cons($name, new Cons($age, null));
        $undefined = "'undefined";

        $this->assertSame($name,      $alist->assoc(":name",   $undefined));
        $this->assertSame($age,       $alist->assoc(":age",    $undefined));
        $this->assertSame($undefined, $alist->assoc(":author", $undefined));
        $this->assertSame($name,      $alist->rassoc("Teto Kasane",   $undefined));
        $this->assertSame($age,       $alist->rassoc(31,    $undefined));
        $this->assertSame($undefined, $alist->rassoc("Miku Hatsune", $undefined));
    }
}
