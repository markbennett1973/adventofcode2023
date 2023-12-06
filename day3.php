<?php

include('common.php');
$input = getInputMap();
print "Part 1: " . getSum($input) . "\n";
print "Part 2: " . getGears($input) . "\n";

function getSum(array $map): int
{
    $sum = 0;
    for ($row = 0; $row < count($map); $row++) {
        $isPartNumber = false;
        $partNumber = '';
        for ($col = 0; $col < count($map[$row]); $col++) {
            if (is_numeric($map[$row][$col])) {
                $partNumber .= $map[$row][$col];
                if (isPartNumber($map, $row, $col)) {
                    $isPartNumber = true;
                }
            } else {
                if ($isPartNumber) {
                    $sum += (int) $partNumber;
                }

                $isPartNumber = false;
                $partNumber = '';
            }
        }

        if ($isPartNumber) {
            $sum += (int) $partNumber;
        }
    }

    return $sum;
}

function isPartNumber(array $map, int $row, int $col): bool
{
    $symbols = 0;
    $symbols += checkSymbol($map, $row-1, $col-1);
    $symbols += checkSymbol($map, $row-1, $col);
    $symbols += checkSymbol($map, $row-1, $col+1);
    $symbols += checkSymbol($map, $row, $col-1);
    $symbols += checkSymbol($map, $row, $col+1);
    $symbols += checkSymbol($map, $row+1, $col-1);
    $symbols += checkSymbol($map, $row+1, $col);
    $symbols += checkSymbol($map, $row+1, $col+1);

    return $symbols > 0;
}

function checkSymbol(array $map, $row, $col): int
{
    if (array_key_exists($row, $map) && array_key_exists($col, $map[$row])) {
        $character = $map[$row][$col];
        if (is_numeric($character) || $character === '.') {
            return false;
        }

        return true;
    }

    return 0;
}

function getGears(array $map): int
{
    $sum = 0;
    for ($row = 0; $row < count($map); $row++) {
        for ($col = 0; $col < count($map[$row]); $col++) {
            if ($map[$row][$col] === '*') {
                $sum += getRatio($map, $row, $col);
            }
        }
    }

    return $sum;
}

function getRatio(array $map, int $row, int $col): int
{
    $digits = [];
    $digits[] = getAdjacentNumber($map, $row-1, $col-1);
    $digits[] = getAdjacentNumber($map, $row-1, $col);
    $digits[] = getAdjacentNumber($map, $row-1, $col+1);
    $digits[] = getAdjacentNumber($map, $row, $col-1);
    $digits[] = getAdjacentNumber($map, $row, $col+1);
    $digits[] = getAdjacentNumber($map, $row+1, $col-1);
    $digits[] = getAdjacentNumber($map, $row+1, $col);
    $digits[] = getAdjacentNumber($map, $row+1, $col+1);

    $uniqueDigits = array_unique($digits);
    $uniqueDigits = array_filter($uniqueDigits, fn ($digit) => is_numeric($digit));

    if (count($uniqueDigits) > 1) {
        return array_product($uniqueDigits);
    }

    return 0;
}

function getAdjacentNumber(array $map, int $row, int $col): ?int
{
    if (!array_key_exists($row, $map) || !array_key_exists($col, $map[$row])) {
        return null;
    }

    if (!is_numeric($map[$row][$col])) {
        return null;
    }

    // shift left until we find a non-digit
    while ($col > 0 && is_numeric($map[$row][$col-1])) {
        $col--;
    }

    // now get the digits
    $number = $map[$row][$col];
    while ($col < count($map[$row])-1 && is_numeric($map[$row][$col+1])) {
        $col++;
        $number .= $map[$row][$col];
    }

    return (int) $number;
}