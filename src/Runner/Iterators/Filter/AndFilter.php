<?php declare(strict_types=1);

namespace PHPUnit\Runner\Iterators\Filter;

use PHPUnit\Runner\Iterators\Filter;
use PHPUnit\Runner\TestMethod;

class AndFilter implements Filter
{
    private $filters;
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }
    public function allowed(TestMethod $method): bool
    {
        foreach ($this->filters as $filter) {
            if (!$filter->allowed($method)) {
                return false;
            }
        }
        return true;
    }
}