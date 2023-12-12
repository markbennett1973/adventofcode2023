<?php

include('common.php');
$input = getInput();
print "Part 1: " . getSum($input, true) . "\n"; // 1819125966
print "Part 2: " . getSum($input, false) . "\n"; // 1819125966

function getSum(array $input, bool $forwards): int
{
    $sum = 0;
    foreach ($input as $line) {
        $sum += getExtrapolatedValue(explode(' ', $line), $forwards);
    }

    return $sum;
}

function getExtrapolatedValue(array $digits, bool $forwards): int
{
    $layers[0] = $digits;
    while(!isFinished(end($layers))) {
        $layers[] = getNextLayer(end($layers));
    }

    // now work back up
    $nextValue = 0;
    for ($layer = count($layers) - 1; $layer >= 0; $layer--) {
        if ($forwards) {
            $nextValue += end($layers[$layer]);
        } else {
            $nextValue = reset($layers[$layer]) - $nextValue;
        }
    }

    return $nextValue;
}

function getNextLayer(array $digits): array
{
    $nextLayer = [];
    for ($i = 0; $i < count($digits)-1; $i++) {
        $nextLayer[] = $digits[$i+1] - $digits[$i];
    }

    return $nextLayer;
}

function isFinished(array $digits): bool
{
    if (max($digits) === 0 && min($digits) === 0) {
        return true;
    }

    return false;
}