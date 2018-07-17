<?php declare(strict_types=1);

namespace PHPUnit\Runner\Iterators\Filter;

use PHPUnit\Runner\Iterators\Filter;
use PHPUnit\Runner\TestMethod;

class InvertFilter implements Filter
{
    private $filter;
    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }
    public function allowed(TestMethod $method): bool
    {
        return !$this->filter->allowed($method);
    }
}