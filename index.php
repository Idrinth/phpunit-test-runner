<?php declare(strict_types=1);

use PHPUnit\Runner\Iterators\Filter;
use PHPUnit\Runner\Iterators\Filter\AnyFilter;
use PHPUnit\Runner\Iterators\Filter\GroupFilter;
use PHPUnit\Runner\Iterators\Filter\InvertFilter;
use PHPUnit\Runner\Iterators\Filter\OrFilter;
use PHPUnit\Runner\TestFinder;
use PHPUnit\Runner\TestMethodExecutor;

require_once(__DIR__.'/vendor/autoload.php');

function filterFromOpts(): Filter
{
    $opts = getopt('',['group:', 'exclude-group:']);
    if (isset($opts['group'])) {
        $groups = [];
        foreach ((array) $opts['group'] as $group) {
            $groups[] = new GroupFilter($group);
        }
        return new OrFilter($groups);
    }
    if (isset($opts['exclude-group'])) {
        $groups = [];
        foreach ((array) $opts['exclude-group'] as $group) {
            $groups[] =new GroupFilter($group);
        }
        return new InvertFilter(new OrFilter($groups));
    }
    return new AnyFilter();
}
$start = microtime(true);
$executor = new TestMethodExecutor();
$tests = 0;

foreach ((new TestFinder)->find([__DIR__.'/test'], filterFromOpts()) as $case) {
    $executor->execute($case);
    $tests++;
}

$duration = microtime(true) - $start;
$memory = memory_get_peak_usage(true)/1000000;
echo "\n\n{$duration}s, {$memory}MB for $tests TestCases\n\n";