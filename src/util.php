<?php

$GLOBALS['OFFSETS'] = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

function isNeighbour($a, $b)
{
    $a = explode(',', $a);
    $b = explode(',', $b);
    if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) {
        return true;
    }
    if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) {
        return true;
    }
    if ($a[0] + $a[1] == $b[0] + $b[1]) {
        return true;
    }
    return false;
}

function hasNeighBour($a, $board)
{
    foreach (array_keys($board) as $b) {
        if (isNeighbour($a, $b)) {
            return true;
        }
    }
    return false;
}

function neighboursAreSameColor($player, $a, $board)
{
    foreach ($board as $b => $st) {
        if (!$st) {
            continue;
        }
        $c = $st[count($st) - 1][0];
        if ($c != $player && isNeighbour($a, $b)) {
            return false;
        }
    }
    return true;
}

function len($tile)
{
    return $tile ? count($tile) : 0;
}

function slide($board, $from, $to)
{
    if (!hasNeighBour($to, $board)) {
        return false;
    }
    if (!isNeighbour($from, $to)) {
        return false;
    }
    $b = explode(',', $to);
    $common = [];
    foreach ($GLOBALS['OFFSETS'] as $pq) {
        $p = $b[0] + $pq[0];
        $q = $b[1] + $pq[1];
        $pos = "$p,$q";
        if (array_key_exists($pos, $board)) {
            $common[] = $pos;
        }
    }
    if (empty($common)) {
        return false;
    }
    $len_common_0 = isset($board[$common[0]]) ? len($board[$common[0]]) : 0;
    $len_common_1 = isset($board[$common[1]]) ? len($board[$common[1]]) : 0;
    $len_from = isset($board[$from]) ? len($board[$from]) : 0;
    $len_to = isset($board[$to]) ? len($board[$to]) : 0;
    return min($len_common_0, $len_common_1) <= max($len_from, $len_to);
}


function getAvailableTiles($hand, $player)
{
    $availableTiles = [];
    foreach ($hand[$player] as $tile => $count) {
        if ($count > 0) {
            $availableTiles[] = $tile;
        }
    }
    return $availableTiles;
}

function getState()
{
    return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
}

function setState($state)
{
    list($a, $b, $c) = unserialize($state);
    $_SESSION['hand'] = $a;
    $_SESSION['board'] = $b;
    $_SESSION['player'] = $c;
}

function isValidMove($board, $from, $to) {

    $fromCoords = explode(',', $from);
    $toCoords = explode(',', $to);

    $dx = $toCoords[0] - $fromCoords[0];
    $dy = $toCoords[1] - $fromCoords[1];

    if ($dx == 0 && $dy == 0) {
        return false;
    }

    if ($to == $from) {
        return false;
    }

    if (isset($board[$to])) {
        return false;
    }

    // Controleer of er minstens één bezet veld tussen de start- en eindposities is
    $x = $fromCoords[0];
    $y = $fromCoords[1];

    $occupiedBetween = false;

    while ($x !== $toCoords[0] || $y !== $toCoords[1]) {
        $pos = "$x,$y";
        echo "Checking position $pos<br>"; 

        if (isset($board[$pos])) {
            $occupiedBetween = true;
        }

        $x += ($dx === 0) ? 0 : $dx / abs($dx);
        $y += ($dy === 0) ? 0 : $dy / abs($dy);

        if ($x == $toCoords[0] && $y == $toCoords[1]) {
            break;
        }
    }

    if (!$occupiedBetween) {
        return false;
    }

    return true;
}



function grasshopper($board, $from, $to)
{
    if (!isValidMove($board, $from, $to)) {
        return false;
    }

    $tile = array_pop($board[$from]);
    $board[$to] = [$tile];
    return $board;
}
