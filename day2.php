<?php

include('common.php');
$input = getInput(false);
print "Part 1: " . getSum($input, ['red'=>12, 'green'=>13, 'blue'=>14]) . "\n";
print "Part 2: " . getSum($input) . "\n";

function getSum(array $games, array $maxValues = null): int
{
    $sum = 0;
    foreach ($games as $game) {
        $parts = explode(':', $game);
        $gameId = str_replace('Game ', '', $parts[0]);
        $sets = explode(';', $parts[1]);

        if ($maxValues) {
            // doing part 1 - check if game is possible
            if (isGamePossible($sets, $maxValues)) {
                $sum += $gameId;
            }
        } else {
            // doing part 2 - get power of game
            $sum += getPower($sets);
        }
    }

    return $sum;
}

function isGamePossible(array $sets, array $maxValues): bool
{
    foreach ($sets as $set) {
        $items = explode(',', $set);
        foreach ($items as $item) {
            $parts = explode(' ',trim($item));
            $colour = trim($parts[1]);
            $count = (int) $parts[0];

            if ($count > $maxValues[$colour]) {
                return false;
            }
        }
    }

    return true;
}

function getPower(array $sets): int
{
    $maxValues = [];

    foreach ($sets as $set) {
        $items = explode(',', $set);
        foreach ($items as $item) {
            $parts = explode(' ',trim($item));
            $colour = trim($parts[1]);
            $count = (int) $parts[0];

            if (!array_key_exists($colour, $maxValues) || $count > $maxValues[$colour]) {
                $maxValues[$colour] = $count;
            }
        }
    }

    return array_product($maxValues);
}