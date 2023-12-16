<?php

include('common.php');
$input = getInputMap();
$expansion = 1;
print "Part 1: " . getPaths($input) . "\n";
$expansion = 999999;
print "Part 2: " . getPaths($input) . "\n";

function getPaths(array $map): int
{
    $emptyRows = getEmptyRows($map);
    $emptyCols = getEmptyCols($map);
    $totalPaths = getAllPaths($map, $emptyRows, $emptyCols);

    return $totalPaths / 2;
}

function getEmptyRows(array $map): array
{
    $emptyRows = [];
    for ($row = 0; $row < count($map); $row++) {
        if (isRowEmpty($map, $row)) {
            $emptyRows[] = $row;
        }
    }
    return $emptyRows;
}

function getEmptyCols(array $map): array
{
    $emptyCols = [];
    for ($col = 0; $col < count($map[0]); $col++) {
        if (isColEmpty($map, $col)) {
            $emptyCols[] = $col;
        }
    }
    return $emptyCols;
}

function isRowEmpty(array $map, int $row): bool
{
    foreach ($map[$row] as $cell) {
        if ('#' === $cell) {
            return false;
        }
    }

    return true;
}

function isColEmpty(array $map, int $col): bool
{
    foreach ($map as $row => $cols) {
        if ('#' === $map[$row][$col]) {
            return false;
        }
    }

    return true;
}

function getAllPaths(array $map, array $emptyRows, array $emptyCols): int
{
    $totalPaths = 0;
    foreach ($map as $row => $rowData) {
        foreach ($rowData as $col => $cell) {
            if ('#' === $cell) {
                $totalPaths += getCellPaths($map, $row, $col, $emptyRows, $emptyCols);
            }
        }
    }

    return $totalPaths;
}

function getCellPaths(array $map, int $startRow, int $startCol, array $emptyRows, array $emptyCols): int
{
    global $expansion;

    $totalDistance = 0;
    foreach ($map as $row => $rowData) {
        foreach ($rowData as $col => $cell) {
            if ('#' === $cell) {
                $distance = abs($startRow - $row) + abs($startCol - $col);

                // add expansion for missing rows and cols
                foreach ($emptyRows as $emptyRow) {
                    if ($emptyRow >= $startRow && $emptyRow <= $row) {
                        $distance += $expansion;
                    }

                    if ($emptyRow >= $row && $emptyRow <= $startRow) {
                        $distance += $expansion;
                    }
                }

                foreach ($emptyCols as $emptyCol) {
                    if ($emptyCol >= $startCol && $emptyCol <= $col) {
                        $distance += $expansion;
                    }

                    if ($emptyCol >= $col && $emptyCol <= $startCol) {
                        $distance += $expansion;
                    }
                }

                $totalDistance += $distance;
            }
        }
    }

    return $totalDistance;
}