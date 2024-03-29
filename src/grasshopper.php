<?php
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