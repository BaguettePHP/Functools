<?php
namespace Teto\External;
use Teto\Functools as f;

use Underbar\ArrayImpl as a_;
use Underbar\IteratorImpl as i_;

final class UnderbarTest extends \PHPUnit_Framework_TestCase
{
    public function test_IteratorImpl()
    {
        $expected = 110;
        $actual = i_::chain(i_::range(1, 11))
            ->map(f::op('*', [2]))
            ->sum();

        $this->assertEquals($expected, $actual);
    }

    public function test_ArrayImpl()
    {
        $expected = 110;
        $actual = a_::chain(a_::range(1, 11))
            ->map(f::op('*', [2]))
            ->sum();

        $this->assertEquals($expected, $actual);
    }
}
