<?php

include('common.php');
$input = getInput();

$useJokers = false;
print "Part 1: " . getWinnings($input) . "\n";
$useJokers = true;
print "Part 2: " . getWinnings($input) . "\n";

function getWinnings(array $input): int
{
    $hands = array_map(fn ($line) => explode(' ', $line), $input);
    usort($hands, 'sortHands');
    $winnings = 0;
    foreach ($hands as $index => $hand) {
        $winnings += ($index + 1) * $hand[1];
    }

    return $winnings;
}

function sortHands(array $a, array $b): int
{
    $handA = getHand($a[0]);
    $handB = getHand($b[0]);

    $handAType = getHandType($handA);
    $handBType = getHandType($handB);

    if ($handAType !== $handBType) {
        return $handAType <=> $handBType;
    }

    foreach ($handA as $index => $card) {
        if ($handA[$index] !== $handB[$index]) {
            return $handA[$index] <=> $handB[$index];
        }
    }

    return 0;
}

function getHand(string $hand): array
{
    global $useJokers;

    $cards = [];
    $search = ['A', 'K', 'Q', 'J', 'T'];
    $replace = $useJokers ? [14, 13, 12, 1, 10] : [14, 13, 12, 11, 10];

    foreach (str_split($hand) as $card) {
        $cards[] = str_replace($search, $replace, $card);
    }

    return $cards;
}

function getHandType(array $hand) : int
{
    global $useJokers;

    $groups = [];
    foreach ($hand as $card) {
        if (array_key_exists($card, $groups)) {
            $groups[$card]++;
        } else {
            $groups[$card] = 1;
        }
    }

    if ($useJokers && array_key_exists(1, $groups)) {
        return getHandTypeWithWildcards($groups);
    }

    return getHandTypeWithoutWildcards($groups);
}

function getHandTypeWithoutWildcards(array $groups): int
{
    switch (max($groups)) {
        case 5:
            // five of a kind
            return 7;
        case 4:
            // four of a kind
            return 6;
        case 3:
            if (in_array(2, $groups)) {
                // full house
                return 5;
            }
            // three of a kind
            return 4;
        case 2:
            if (count($groups) === 3) {
                // two pairs
                return 3;
            }
            return 2;
        case 1:
            // high card
            return 1;
    }

    throw new Exception('Failed to get hand type for '.print_r($groups, true));
}

function getHandTypeWithWildcards(array $groups): int
{
    $jokers = $groups[1];
    unset($groups[1]);

    if ($jokers === 5) {
        return 7;
    }

    $sameCards = max($groups) + $jokers;

    if ($sameCards === 5) {
        // five of a kind
        return 7;
    }

    if ($sameCards === 4) {
        // four of a kind
        return 6;
    }

    if (count($groups) === 2) {
        // full house
        return 5;
    }

    if ($sameCards === 3) {
        // three of a kind
        return 4;
    }

    if (count($groups) === 3) {
        // two pairs
        return 3;
    }

    // with any jokers, we can always get at least a pair
    return 2;
}