<?php

include('common.php');
$input = getInput();
print "Part 1: " . getSum($input) . "\n";
print "Part 2: " . getCount($input) . "\n";

function getSum(array $cards): int
{
    $sum = 0;

    foreach (getMatches($cards) as $index => $matches) {
        if ($matches > 0) {
            $sum += pow(2, $matches - 1);
        }
    }

    return $sum;
}

function getMatches(array $cards) {
    $totalMatches = [];
    foreach ($cards as $card) {
        preg_match('/Card[ ]+([\d]+):/', $card, $matches);
        $cardNumber = $matches[1];
        $totalMatches[$cardNumber] = getCardScore($card);
    }

    return $totalMatches;
}

function getCardScore(string $card): int
{
    $parts = explode(':', $card)[1];
    $parts = explode('|', $parts);

    $winningNumbers = array_filter(explode(' ', $parts[0]));
    $myNumbers = array_filter(explode(' ', $parts[1]));
    $matches = count(array_intersect($winningNumbers, $myNumbers));
    return $matches;
}

function getCount($cards): int
{
    $matches = getMatches($cards);

    $cardCounts = array_fill(1, count($matches), 1);

    foreach ($matches as $cardIndex => $matchCount) {
        for ($i = 1; $i <= $matchCount; $i++) {
            $cardCounts[$cardIndex + $i] += $cardCounts[$cardIndex];
        }
    }

    return array_sum($cardCounts);
}