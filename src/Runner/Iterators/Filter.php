<?php

namespace PHPUnit\Runner\Iterators;

use PHPUnit\Runner\TestMethod as TestMethodDTO;

interface Filter
{
    public function allowed(TestMethodDTO $method): bool;
}