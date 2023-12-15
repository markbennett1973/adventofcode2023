<?php

include('common.php');
$input = getInputMap();
print "Part 1: " . getDistance($input) . "\n";
// print "Part 2: " . getDistance($input) . "\n";

function getDistance(array $map): int
{
    $startNode = findStart($map);
    $visitedNodes = [$startNode];
    $currentNodes = [$startNode];
    $steps = 0;
    while (true) {
        $steps++;
        $currentNodes = getNewNodes($map, $currentNodes, $visitedNodes);
        $visitedNodes = array_merge($visitedNodes, $currentNodes);
        if (isFinished($currentNodes)) {
            return $steps;
        }
    }
}

function findStart(array $map): string
{
    $rows = count($map);
    $cols = count($map[0]);
    for ($row = 0; $row < $rows; $row++) {
        for ($col = 0; $col < $cols; $col++) {
            if ($map[$row][$col] === 'S') {
                return $row.','.$col;
            }
        }
    }

    throw new Exception('Starting position not found');
}

function getNewNodes(array $map, array $currentNodes, array $visitedNodes): array
{
    $newNodes = [];
    $directions = ['U', 'D', 'L', 'R'];
    foreach ($currentNodes as $currentNode) {
        list($row, $col) = explode(',', $currentNode);
        foreach ($directions as $direction) {
            if ($newNode = canMove($map, $row, $col, $direction, $visitedNodes)) {
                $newNodes[] = $newNode;
            }
        }
    }

    return $newNodes;
}

function canMove(array $map, int $currentRow, int $currentCol, string $direction, array $visitedNode): ?string
{
    $currentChar = $map[$currentRow][$currentCol];
    $newNode = null;

    switch ($direction) {
        case 'U':
            if ($currentRow === 0) {
                // can't move up
                return null;
            }

            $nextChar = $map[$currentRow - 1][$currentCol];
            if (in_array($currentChar, ['S', '|', 'L', 'J']) && in_array($nextChar, ['|', '7', 'F'])) {
                // can move up from current char to next char
                $newNode = ($currentRow - 1) . ',' . $currentCol;
            }
            break;

        case 'D':
            if ($currentRow + 1 >= count($map)) {
                // can't move down
                return null;
            }

            $nextChar = $map[$currentRow + 1][$currentCol];
            if (in_array($currentChar, ['S', '|', '7', 'F']) && in_array($nextChar, ['|', 'L', 'J'])) {
                // can move down from current char to next char
                $newNode = ($currentRow + 1) . ',' . $currentCol;
            }
            break;

        case 'L':
            if ($currentCol === 0) {
                // can't move left
                return null;
            }

            $nextChar = $map[$currentRow][$currentCol - 1];
            if (in_array($currentChar, ['S', '-', '7', 'J']) && in_array($nextChar, ['-', 'L', 'F'])) {
                // can move left from current char to next char
                $newNode = ($currentRow) . ',' . ($currentCol - 1);
            }
            break;

        case 'R':
            if ($currentCol + 1 >= count($map[0])) {
                // can't move right
                return null;
            }

            $nextChar = $map[$currentRow][$currentCol + 1];
            if (in_array($currentChar, ['S', '-', 'L', 'F']) && in_array($nextChar, ['-', '7', 'J'])) {
                // can move left from current char to next char
                $newNode = ($currentRow) . ',' . ($currentCol + 1);
            }
            break;
    }

    return in_array($newNode, $visitedNode) ? null : $newNode;
}

function isFinished(array $currentNodes): bool
{
    // We've finished if we've reached the same node in two different ways
    $uniqueNodes = count(array_unique($currentNodes));
    return count($currentNodes) !== $uniqueNodes;
}