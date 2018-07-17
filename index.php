<?php declare(strict_types=1);

use PHPUnit\Runner\TestFinder;

require_once(__DIR__.'/vendor/autoload.php');

$start = microtime(true);
$executor = new PHPUnit\Runner\TestMethodExecutor();
$tests = 0;
foreach ((new TestFinder)->find([__DIR__.'/test']) as $case) {
    $executor->execute($case);
    $tests++;
}
$duration = microtime(true) - $start;
$memory = memory_get_peak_usage(true)/1000000;
echo "\n\n{$duration}s, {$memory}MB for $tests TestCases\n\n";