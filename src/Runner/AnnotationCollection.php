<?php declare(strict_types=1);

namespace PHPUnit\Runner;

use Generator;
use InvalidArgumentException;

class AnnotationCollection
{
    /**
     * @var Annotation[][]
     */
    private $annotations = [];
    public function add(Annotation $annotation): void
    {
        $this->annotations[$annotation->getName()] = $this->annotations[$annotation->getName()] ?? [];
        $this->annotations[$annotation->getName()][] = $annotation;
    }

    public function has(string $annotation): bool
    {
        return isset($this->annotations[$annotation]);
    }

    /**
     * @param string $annotation
     * @return Generator|Annotation[]
     * @throws InvalidArgumentException
     */
    public function get(string $annotation): iterable
    {
        if (!$this->has($annotation)) {
            throw new InvalidArgumentException("$annotation is unknown.");
        }
        foreach($this->annotations[$annotation] as $instance) {
            yield clone $instance;
        }
    }
}