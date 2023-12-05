<?php

include('common.php');
$input = getInput(false);
print "Part 1: " . getSum($input, false) . "\n";
print "Part 2: " . getSum($input, true) . "\n";

function getSum(array $lines, bool $clean): int
{
    $sum = 0;
    foreach ($lines as $line) {
        $sum += getValue($line, $clean);
    }

    return $sum;
}

function getValue(string $line, bool $clean): int
{
    if ($clean)  {
        $line = str_replace(
            ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'],
            ['o1e', 't2o', 'th3ee', 'f4ur', 'f5ve', 's6x', 'se7en', 'ei8ht', 'n9ne'],
            $line
        );
    }

    preg_match_all('[\d]', $line, $matches);
    $value = reset($matches[0]) . end($matches[0]);
    return (int) $value;
}
