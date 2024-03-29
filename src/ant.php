<?php
include_once 'util.php';

function isValidAntMove($board, $from, $to)
{
    $visited = [];

    $positionsToExplore = [$from];

    while (!empty($positionsToExplore)) {

        $current = array_shift($positionsToExplore);

        if ($current == $to) {
            return true;
        }

        $visited[$current] = true;

        foreach ($GLOBALS['OFFSETS'] as $pq) {
            $p = explode(',', $current)[0] + $pq[0];
            $q = explode(',', $current)[1] + $pq[1];
            $position = "$p,$q";


            if (!isset($board[$position]) && !isset($visited[$position]) && hasNeighbour($position, $board)) {

                $positionsToExplore[] = $position;
            }
        }
    }

    return false;
}

function moveAnt($board, $from, $to)
{
    if (!isValidAntMove($board, explode(',', $from), $to)) {
        return false;
    }

    // Verplaats de mier
    $tile = array_pop($board[$from]);
    $board[$to] = [$tile];

    // Verwijder de mier van zijn oorspronkelijke positie
    unset($board[$from]);

    return $board;
}
