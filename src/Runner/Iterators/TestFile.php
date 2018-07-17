<?php declare(strict_types=1);

namespace PHPUnit\Runner\Iterators;

use Generator;
use IteratorAggregate;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Traversable;

class TestFile implements IteratorAggregate
{
    private $directories = [];
    private $endsWith;
    public function __construct(array $directories, string $endsWith='Test.php')
    {
        $this->endsWith = $endsWith;
        $this->directories = $directories;
    }

    /**
     * @return Generator|string[]
     */
    public function getIterator(): Traversable
    {
        foreach ($this->directories as $directory) {
            yield from new RegexIterator(
                new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator(
                        $directory,
                        RecursiveDirectoryIterator::CURRENT_AS_PATHNAME|RecursiveDirectoryIterator::SKIP_DOTS
                    )
                ),
                '/.+'.preg_quote($this->endsWith).'$/i'
            );
        }
    }
}