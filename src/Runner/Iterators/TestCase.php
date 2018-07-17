<?php declare(strict_types=1);

namespace PHPUnit\Runner\Iterators;

use Generator;
use IteratorAggregate;
use PHPUnit\Runner\TestMethod as TestMethodDTO;
use Traversable;

class TestCase implements IteratorAggregate
{
    private $methods;
    public function __construct(TestMethod $methods)
    {
        $this->methods = $methods;
    }
    public function getIterator(): Traversable
    {
        foreach($this->methods as $method) {
            $usedData = false;
            if($method->methodLevelAnnotations()->has('testWith')) {
                yield from $this->yieldFromTestWith($method);
                $usedData = true;
            }
            if($method->methodLevelAnnotations()->has('dataProvider')) {
                yield from $this->yieldFromDataProvider($method);
                $usedData = true;
            }
            if (!$usedData) {
                yield $method;
            }
        }
    }
    private function yieldFromDataProvider(TestMethodDTO $method): Generator
    {
        require_once $method->sourceFile();
        $class = $method->className();
        $instance = new $class();
        foreach ($method->methodLevelAnnotations()->get('dataProvider') as $provider) {
            foreach ($instance->{$provider->getValue()}() as $setName => $setData) {
                yield $this->dataSetToMethod($method, $setName, $setData, 'self::'.$provider->getValue());
            }
        }
    }
    private function yieldFromTestWith(TestMethodDTO $method): Generator
    {
        foreach ($method->methodLevelAnnotations()->get('testWith') as $pos => $testWith) {
            foreach (json_decode($testWith->getValue(), true)??[] as $setName => $setData) {
                yield $this->dataSetToMethod($method, $setName, $setData, '@with-'.($pos+1));
            }
        }
    }
    private function dataSetToMethod(TestMethodDTO $method, $name, array $data, string $provider): TestMethodDTO
    {
        return $method->injectData(
            is_int($name) ? "$provider#$name" : '"' . $name . '"',
            $data
        );
    }
}