<?php declare(strict_types=1);

namespace PHPUnit\Runner\Iterators;

use Generator;
use IteratorAggregate;
use PHPUnit\Runner\Annotation;
use PHPUnit\Runner\AnnotationCollection;
use PHPUnit\Runner\TestMethod as TestMethodDTO;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use Traversable;

class TestMethod implements IteratorAggregate
{
    private $classes;
    public function __construct(TestClass $classes)
    {
        $this->classes = $classes;
    }

    /**
     * @return Generator|TestMethodDTO[]
     */
    public function getIterator(): Traversable
    {
        foreach ($this->classes as $file => $class) {
            $className             = $class->getName();
            $classLevelAnnotations = $this->annotations($class->getDocComment());

            foreach ($class->getMethods() as $method) {
                if (!$this->isTestableMethod($method)) {
                    continue;
                }
                $methodAnnotations = $this->annotations($method->getDocComment());
                if (!$this->isTestMethod($method, $methodAnnotations)) {
                    continue;
                }
                yield new TestMethodDTO(
                    $file,
                    $className,
                    $method->getName(),
                    $classLevelAnnotations,
                    $methodAnnotations
                );
            }
        }
    }

    private function annotations(string $docBlock): AnnotationCollection
    {
        $annotations = new AnnotationCollection;
        $docBlock    = (string) \substr($docBlock, 3, -2);

        if (\preg_match_all('/@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?$/m', $docBlock, $matches)) {
            $numMatches = \count($matches[0]);

            for ($i = 0; $i < $numMatches; ++$i) {
                if ($matches['name'][$i] === 'testWith') {
                    continue;
                }
                $annotations->add(
                    new Annotation(
                        (string) $matches['name'][$i],
                        (string) $matches['value'][$i]
                    )
                );
            }
            if (\preg_match_all('/@(?P<name>testWith)[\t ](?P<value>(\[.*?\]|[\n\r]+[\t ]*\*[\t ]+)+)/', $docBlock, $matches)) {
                foreach($matches['value'] as $value) {
                    $annotations->add(
                        new Annotation(
                            'testWith',
                            '[' . implode(',', preg_split('/[ \t]*[\r\n][ \t]*\*[ \t]*/', $value)) . ']'
                        )
                    );
                }
            }
        }

        return $annotations;
    }

    private function isTestableMethod(ReflectionMethod $method): bool
    {
        if ($method->isAbstract() || !$method->isPublic()) {
            return false;
        }

        if ($method->getDeclaringClass()->getName() === TestCase::class) {
            return false;
        }

        return true;
    }

    private function isTestMethod(ReflectionMethod $method, AnnotationCollection $annotations): bool
    {
        if (\strpos($method->getName(), 'test') === 0) {
            return true;
        }

        return $annotations->has('test') || $annotations->has('testWith');
    }
}