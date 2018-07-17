<?php declare(strict_types=1);

namespace PHPUnit\Runner;

final class TestMethod
{
    /**
     * @var string
     */
    private $sourceFile;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var AnnotationCollection
     */
    private $classLevelAnnotations;

    /**
     * @var AnnotationCollection
     */
    private $methodLevelAnnotations;

    /**
     * @var array
     */
    private $setData = [];

    /**
     * @var string|null
     */
    private $setName;

    public function __construct(string $sourceFile, string $className, string $methodName, AnnotationCollection $classLevelAnnotations, AnnotationCollection $methodLevelAnnotations)
    {
        $this->sourceFile             = $sourceFile;
        $this->className              = $className;
        $this->methodName             = $methodName;
        $this->classLevelAnnotations  = $classLevelAnnotations;
        $this->methodLevelAnnotations = $methodLevelAnnotations;
    }

    public function sourceFile(): string
    {
        return $this->sourceFile;
    }

    public function className(): string
    {
        return $this->className;
    }

    public function methodName(): string
    {
        return $this->methodName;
    }

    public function classLevelAnnotations(): AnnotationCollection
    {
        return $this->classLevelAnnotations;
    }

    public function methodLevelAnnotations(): AnnotationCollection
    {
        return $this->methodLevelAnnotations;
    }

    public function injectData(string $name, array $data): TestMethod
    {
        $this->setName = $name;
        $this->setData = $data;
        return $this;
    }

    public function dataSet(): array
    {
        return $this->setData;
    }

    public function nameSet(): ?string
    {
        return $this->setName;
    }
}