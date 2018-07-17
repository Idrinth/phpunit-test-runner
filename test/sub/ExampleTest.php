<?php declare(strict_types=1);

namespace PHPUnitExamples\sub;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testA(): void
    {
        echo json_encode(func_get_args());
    }
    /**
     * @test
     */
    public function b(): int
    {
        echo json_encode(func_get_args());
        return 17;
    }
    public function c()
    {
    }
    /**
     * @testWith [1,2]
     *           [8,9]
     *           [19,21]
     */
    public function d($a, $b): void
    {
        echo json_encode(func_get_args());
    }
    /**
     * @test
     * @dataProvider f
     */
    public function e($a, $b): void
    {
        echo json_encode(func_get_args());
    }
    public function f()
    {
        yield "example" => [100, 200];
        yield 1 => [101, 201];
        yield 13 => [123, 223];
        yield "none" => [99, 199];
    }
    /**
     * @test
     * @dataProvider f
     * @testWith [1,2]
     *           [8,9]
     *           [19,21]
     */
    public function g($b, $c)
    {
        echo json_encode(func_get_args());
    }
    public function h()
    {
        for($i=0;$i<10;$i++) {
            yield [$i, $i+1];
        }
    }
    /**
     * @test
     * @dataProvider f
     * @dataProvider h
     * @testWith [1,2]
     *           [8,9]
     *           [19,21]
     * @testWith [2,2]
     *           [3,9]
     *           [4,21]
     */
    public function i($b, $c)
    {
        echo json_encode(func_get_args());
    }
    private function testJ()
    {
    }
}