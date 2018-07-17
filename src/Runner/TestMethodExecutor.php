<?php declare(strict_types=1);

namespace PHPUnit\Runner;

final class TestMethodExecutor
{
    public function execute(TestMethod $testMethod): void
    {
        require_once $testMethod->sourceFile();

        $className  = $testMethod->className();
        $methodName = $testMethod->methodName();

        $test = new $className;

        if (null === $testMethod->nameSet()) {
            echo "$className::$methodName:\n";
            $test->$methodName();
        } else {
            echo "$className::$methodName with data set {$testMethod->nameSet()}:\n";
            $test->$methodName(...$testMethod->dataSet());
        }
        echo "\n";
    }
}