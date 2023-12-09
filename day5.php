<?php

include('common.php');
$input = getInput();
print "Part 1: " . getLowestLocation($input, false) . "\n";
// TODO: can't brute-force part 2...
// print "Part 2: " . getLowestLocation($input, true) . "\n";

function getLowestLocation(array $input, bool $useSeedRanges): int
{
    $seeds = getSeeds($input, $useSeedRanges);
    $maps = getMaps($input);

    $locations = array_map(function (int $seed) use ($maps){
        return applyMaps($seed, $maps);
    }, $seeds);

    return min($locations);
}

function getSeeds(array $input, bool $useSeedRanges): array
{
    $seeds = explode(' ', $input[0]);
    unset($seeds[0]);
    $seeds = array_values($seeds);

    if (!$useSeedRanges) {
        return $seeds;
    }

    $allSeeds = [];
    for ($i = 0; $i < count($seeds); $i = $i + 2) {
        $startSeed = $seeds[$i];
        $length = $seeds[$i+1];

        for ($j = 0; $j < $length; $j++) {
            $allSeeds[] = $startSeed + $j;
        }
        print "Done $i\n";
    }

    print "Total seeds: ".count($allSeeds)."\n";
    return $allSeeds;
}

function getMaps(array $input): array
{
    $maps = [];
    unset($input[0]);
    unset($input[2]);
    $input = array_filter($input);

    $map = [];
    foreach ($input as $line) {
        if (is_numeric(substr($line, 0, 1))) {
            $map[] = explode(' ', $line);
        } else {
            $maps[] = $map;
            $map = [];
        }
    }

    $maps[] = $map;

    return $maps;
}

function applyMaps(int $seed, array $maps): int
{
    foreach ($maps as $map) {
        $seed = applyMap($seed, $map);
    }

    return $seed;
}

function applyMap(int $seed, array $map) : int
{
    foreach ($map as $range) {
        list($destSTart, $sourceStart, $length) = $range;
        if ($seed >= $sourceStart && $seed <= $sourceStart + $length) {
            return $seed - $sourceStart + $destSTart;
        }
    }

    return $seed;
}