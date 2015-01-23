<?php
namespace Teto\Functools;

final class PartialCallableTest extends \PHPUnit_Framework_TestCase
{
    public function test_partial()
    {
        $func = function ($a, $b, $c) { return $a.$b.$c; };
        $p1 = new PartialCallable($func, [2 => "c"], 1);

        $p2 = $p1->partial([0 => "A"]);
        $this->assertInstanceOf('Teto\Functools\PartialCallable', $p2);

        $this->assertEquals("ABc", $p2("B"));
    }

    /**
     * @dataProvider dataProviderFor_partial_negative
     */
    public function test_partial_negative($expected, $input)
    {
        $func = function ($a = "X", $b = "Y", $c = "Z") {
            return [$a.$b.$c, func_get_args()];
        };

        $p = new PartialCallable($func, $input, -1);

        list($actual, $received_args) = $p("c");

        $this->assertEquals($expected, $actual);
        $this->assertEquals($input, $received_args);
    }

    public function dataProviderFor_partial_negative()
    {
        return [
            [
                'expected' => "AbC",
                'input'    => ["A", "b", "C"],
            ],
            [
                'expected' => "AbZ",
                'input'    => ["A", "b"],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderFor_array_map
     */
    public function test_with_array_map($expected, $callable, $default_args, $pos, $input)
    {
        $patial_applicated = new PartialCallable($callable, $default_args, $pos);
        $actual = array_map($patial_applicated, $input);

        $this->assertSame($expected, $actual);
    }

    public function dataProviderFor_array_map()
    {
        return [
            [
                'expected' => [
                    ['a', 'b', 'c'],
                    ['d', 'e', 'f'],
                ],
                'callable' => 'array_column',
                'default_args' => [1 => "hoge"],
                'pos' => 0,
                'input' => [
                    [
                        ['hoge' => "a", "fuga" => "1"],
                        ['hoge' => "b", "fuga" => "1"],
                        ['hoge' => "c", "fuga" => "1"],
                    ],
                    [
                        ['hoge' => "d", "fuga" => "1"],
                        ['hoge' => "e", "fuga" => "1"],
                        ['hoge' => "f", "fuga" => "1"],
                    ],
                ],
            ],
        ];
    }
}
