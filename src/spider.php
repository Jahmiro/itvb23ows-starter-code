<?php
include_once 'util.php';
function isValidSpiderMove($board, $from, $to)
{
    $visited = [];
    $steps = 0;

    $positionsToExplore = [$from];

    while (!empty($positionsToExplore)) {
        $current = array_shift($positionsToExplore);

        echo "Exploring position: $current\n"; // Debug-uitvoer

        if ($current == $to) {
            echo "Destination position reached\n"; // Debug-uitvoer
            return true;
        }

        $visited[$current] = true;
        $steps++;

        if ($steps >= 3) {
            break; // Verlaat de lus als de spin al drie stappen heeft gezet
        }

        foreach ($GLOBALS['OFFSETS'] as $pq) {
            $p = explode(',', $current)[0] + $pq[0];
            $q = explode(',', $current)[1] + $pq[1];

            // Sla diagonale posities over
            if ($pq[0] != 0 && $pq[1] != 0) {
                continue;
            }

            $position = "$p,$q";

            if ($position == $from || isset($visited[$position])) {
                continue; // Overslaan als het dezelfde positie is als het startpunt of al bezocht
            }

            // Controleer of de buurpositie leeg is
            if (isset($board[$position])) {
                continue;
            }

            // Voeg de positie toe aan de lijst met te verkennen posities
            echo "Adding position to explore: $position\n"; // Debug-uitvoer
            $positionsToExplore[] = $position;
        }
    }

    echo "No path found to destination\n"; // Debug-uitvoer
    return false;
}
