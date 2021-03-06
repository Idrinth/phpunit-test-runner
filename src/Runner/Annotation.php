<?php declare(strict_types=1);

namespace PHPUnit\Runner;

class Annotation
{
    private $name;
    private $value;
    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getValue()
    {
        return $this->value;
    }
}