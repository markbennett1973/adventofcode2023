<?php

include('common.php');
$input = getInput();
print "Part 1: " . getCount($input, false) . "\n";
// TODO: part 2 gets too big
// print "Part 2: " . getCount($input, true) . "\n";

function getCount(array $lines, bool $unfold): int
{
    $count = 0;
    foreach ($lines as $line) {
        list($springs, $groups) = explode(' ', $line);
        if ($unfold) {
            $unfoldedSprings = $unfoldedGroups = '';
            for ($i = 0; $i < 5; $i++) {
                $unfoldedSprings .= $springs;
                $unfoldedGroups .= $groups.',';
            }
            $springs = $unfoldedSprings;
            $groups = rtrim($unfoldedGroups, ',');
        }

        $count += getValidArrangements($springs, $groups);
    }

    return $count;
}

function getValidArrangements(string $springs, string $groups): int
{
    $arrangements = 0;
    $wildcards = [];
    $groupsRegex = getGroupsRegex($groups);
    $springs = str_replace(['.', '#'], [0, 1], $springs);
    foreach (str_split($springs) as $index => $char) {
        if ('?' === $char) {
            $wildcards[] = $index;
        }
    }

    $max = pow(2, count($wildcards));
    print "Checking $max arrangements...";
    $strFormat = '%0'.count($wildcards).'d';
    for ($i = 0; $i < $max; $i++) {
        $bin = sprintf( $strFormat, decbin($i));
        foreach ($wildcards as $index => $pos) {
            $springs[$pos] = $bin[$index];
        }

        if (preg_match($groupsRegex, '0'.$springs.'0')) {
            $arrangements++;
        }
    }
    print " found $arrangements\n";
    return $arrangements;
}

function getGroupsRegex(string $groups): string
{
    // i => /^0+1{i}0+$/
    // i,j => /^0+1{i}0+1{j}0+$/
    // i,j,k => /^0+1{i}0+1{k}0+1{j}0+$/
    $regex = '/^0+';
    foreach (explode(',', $groups) as $group) {
        $regex .= '1{'.$group.'}0+';
    }

    $regex .= '$/';

    return $regex;
}