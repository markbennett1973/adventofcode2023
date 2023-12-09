<?php

include('common.php');
$input = getInput();
print "Part 1: " . getTotal($input, false) . "\n";
print "Part 2: " . getTotal($input, true) . "\n";

function getTotal(array $input, bool $collapseInputs): int
{
    $total = 1;
    $times = getDigits($input[0], $collapseInputs);
    $distances = getDigits($input[1], $collapseInputs);

    foreach ($times as $index => $time) {
        $total *= getWaysToWin($time, $distances[$index]);
    }

    return $total;
}

function getDigits(string $line, bool $collapseInputs): array
{
    if ($collapseInputs) {
        preg_match_all('/[\d+]/', $line, $matches);
        $digits = implode('', $matches[0]);
        return [$digits];
    }

    $digits = array_filter(explode(' ', $line));
    array_shift($digits);

    return array_values($digits);
}

function getWaysToWin(int $raceTime, int $maxDistance): int
{
    $waysToWin = 0;

    for ($buttonTime = 1; $buttonTime < $raceTime; $buttonTime++) {
        $racingTime = $raceTime - $buttonTime;
        $distanceTravelled = $racingTime * $buttonTime;
        if ($distanceTravelled > $maxDistance) {
            $waysToWin++;
        }
    }

    return $waysToWin;
}