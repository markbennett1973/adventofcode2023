<?php
declare(strict_types=1);

/**
 * Read the input.txt file and return as an array of all non-empty lines
 * @param bool $removeBlankLines
 * @return array
 */
function getInput(bool $removeBlankLines = true): array
{
    $lines = explode("\n", file_get_contents('input.txt'));

    if ($removeBlankLines) {
        $lines = array_filter($lines, function ($line) {
            return $line !== '';
        });
    }

    return $lines;
}

/**
 * Read the input.txt file and return as a 2-dimensional array of integers
 * @return array
 */
function getInputMap(): array
{
    $lines = explode("\n", file_get_contents('input.txt'));

    // Remove blank lines
    $lines = array_filter($lines, function ($line) {
        return $line !== '';
    });

    $map = [];
    foreach ($lines as $line) {
        $map[] = array_map(function ($cell) {
            return (int) $cell;
        }, str_split($line));
    }

    return $map;
}