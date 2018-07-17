<?php declare(strict_types=1);

namespace PHPUnitExamples\sub;

use PHPUnit\Framework\TestCase;

/**
 * @group four
 */
class Example2Test extends TestCase
{
    public function testA(): void
    {
        echo json_encode(func_get_args());
    }
}