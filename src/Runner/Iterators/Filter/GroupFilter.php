<?php

namespace PHPUnit\Runner\Iterators\Filter;

use PHPUnit\Runner\Annotation;
use PHPUnit\Runner\Iterators\Filter;
use PHPUnit\Runner\TestMethod;

class GroupFilter implements Filter
{
    private $group;
    public function __construct(string $group)
    {
        $this->group = $group;
    }
    public function allowed(TestMethod $method): bool
    {
        if ($method->classLevelAnnotations()->has('group')) {
            if ($this->groupInList($method->classLevelAnnotations()->get('group'))) {
                return true;
            }
        }
        if ($method->methodLevelAnnotations()->has('group')) {
            if ($this->groupInList($method->methodLevelAnnotations()->get('group'))) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param iterable|Annotation[] $list
     * @return boolean
     */
    private function groupInList(iterable $list)
    {
        foreach ($list as $item) {
            if ($item->getValue() === $this->group) {
                return true;
            }
        }
        return false;
    }
}