<?php declare(strict_types=1);

namespace PHPUnit\Runner\Iterators\Filter;

use PHPUnit\Runner\Iterators\Filter;
use PHPUnit\Runner\TestMethod;

class AnyFilter implements Filter
{
    public function allowed(TestMethod $method): bool
    {
        return true;
    }
}