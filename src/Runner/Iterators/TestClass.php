<?php declare(strict_types=1);

namespace PHPUnit\Runner\Iterators;

use Generator;
use IteratorAggregate;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Exception\EmptyPhpSourceCode;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\AutoloadSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\PhpInternalSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\StringSourceLocator;
use PHPUnit\Framework\TestCase;
use Traversable;

class TestClass implements IteratorAggregate
{
    private $files;
    public function __construct(TestFile $files)
    {
        $this->files = $files;
    }

    /**
     * @return Generator|ReflectionClass[]
     */
    public function getIterator(): Traversable
    {
        foreach($this->files as $file) {
            $file = realpath($file);
            foreach((new ClassReflector($this->createSourceLocator(file_get_contents($file))))->getAllClasses() as $class) {
                if ($this->isTestClass($class)) {
                    yield $file => $class;
                }
            }
        }
    }

    private function isTestClass(ReflectionClass $class): bool
    {
        return !$class->isAbstract() && $class->isSubclassOf(TestCase::class);
    }

    /**
     * @throws EmptyPhpSourceCode
     */
    private function createSourceLocator(string $source): AggregateSourceLocator
    {
        $astLocator = (new BetterReflection())->astLocator();

        return new AggregateSourceLocator(
            [
                new StringSourceLocator($source, $astLocator),
                new AutoloadSourceLocator($astLocator),
                new PhpInternalSourceLocator($astLocator)
            ]
        );
    }
}