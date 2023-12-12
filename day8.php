<?php

include('common.php');
$input = getInput();
print "Part 1: " . getSteps($input) . "\n";
// TODO: can't brute force part 2
// print "Part 2: " . getSimultaneousSteps($input) . "\n";

function getSteps(array $input): int
{
    $moves = str_split($input[0]);
    $totalMoves = count($moves);
    $nodes = getNodes($input);

    $currentMove = $stepCount = 0;
    $currentNode = 'AAA';
    while ($currentNode !== 'ZZZ') {
        $move = $moves[$currentMove];
        if ($move === 'L') {
            $currentNode = $nodes[$currentNode][0];
        } else {
            $currentNode = $nodes[$currentNode][1];
        }

        $stepCount++;
        $currentMove++;
        if ($currentMove === $totalMoves) {
            $currentMove = 0;
        }
    }

    return $stepCount;
}

function getNodes(array $input): array
{
    $nodes = [];
    unset($input[0]);
    unset($input[1]);
    foreach ($input as $line) {
        preg_match('/([\w]+) = \(([\w]+), ([\w]+)\)/', $line, $matches);
        $nodes[$matches[1]] = [$matches[2], $matches[3]];
    }

    return $nodes;
}

function getSimultaneousSteps(array $input): int
{
    $moves = str_split($input[0]);
    $totalMoves = count($moves);
    $nodes = getNodes($input);

    $currentMove = $stepCount = 0;
    $currentNodes = array_values(array_filter(array_keys($nodes), fn (string $key) => str_ends_with($key, 'A')));
    while (!isFinished($currentNodes)) {
        $move = $moves[$currentMove];
        foreach ($currentNodes as $index => $currentNode) {
            if ($move === 'L') {
                $currentNodes[$index] = $nodes[$currentNode][0];
            } else {
                $currentNodes[$index] = $nodes[$currentNode][1];
            }
        }

        $stepCount++;
        $currentMove++;
        if ($currentMove === $totalMoves) {
            $currentMove = 0;
        }

        if ($stepCount % 1000000 === 0) {
            print "Done ".number_format($stepCount)." steps\n";
        }
    }

    return $stepCount;
}

function isFinished(array $currentNodes): bool
{
    foreach ($currentNodes as $currentNode) {
        if (!str_ends_with($currentNode, 'Z')) {
            return false;
        }
    }

    return true;
}