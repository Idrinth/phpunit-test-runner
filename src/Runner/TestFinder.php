<?php declare(strict_types=1);

namespace PHPUnit\Runner;

use Generator;
use PHPUnit\Runner\Iterators\TestCase;
use PHPUnit\Runner\Iterators\TestClass;
use PHPUnit\Runner\Iterators\TestFile;
use Traversable;

class TestFinder
{
    /**
     * @param array $directories
     * @return Generator|TestMethod[]
     */
     public function find(array $directories): Traversable
     {
        yield from new TestCase(
             new Iterators\TestMethod(
                new TestClass(
                   new TestFile($directories)
               )
             )
         );
     }
}