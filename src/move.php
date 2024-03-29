<?php
session_start();

include_once 'util.php';
require_once 'grasshopper.php';
require_once 'ant.php'; // Include the ant logic

$from = $_POST['from'];
$to = $_POST['to'];

$player = $_SESSION['player'];
$board = $_SESSION['board'];
$hand = $_SESSION['hand'][$player];
unset($_SESSION['error']);

if (!isset($board[$from])) {
    $_SESSION['error'] = 'Board position is empty';
} elseif (count($board[$from]) == 0 || $board[$from][count($board[$from]) - 1][0] != $player) {
    $_SESSION['error'] = "Tile is not owned by player";
} else {
    $tile = array_pop($board[$from]);
    if (!hasNeighBour($to, $board)) {
        $_SESSION['error'] = "Move would split hive";
    } else {
        $all = array_keys($board);
        $queue = [array_shift($all)];
        while ($queue) {
            $next = explode(',', array_shift($queue));
            foreach ($GLOBALS['OFFSETS'] as $pq) {
                list($p, $q) = $pq;
                $p += $next[0];
                $q += $next[1];
                if (in_array("$p,$q", $all)) {
                    $queue[] = "$p,$q";
                    $all = array_diff($all, ["$p,$q"]);
                }
            }
        }
        if ($all) {
            $_SESSION['error'] = "Move would split hive";
        } else {
            if ($from == $to) {
                $_SESSION['error'] = 'Tile must move';
            } elseif (isset($board[$to]) && $tile[1] != "B") {
                $_SESSION['error'] = 'Tile not empty';
            } elseif ($tile[1] == "Q" || $tile[1] == "B") {
                if (!slide($board, $from, $to)) {
                    $_SESSION['error'] = 'Tile must slide';
                }
            } elseif ($tile[1] == "G") {
                $validMove = isValidMove($board, $from, $to);
                if (!$validMove) {
                    $_SESSION['error'] = "Invalid move for grasshopper: Invalid move from $from to $to";
                }
            } elseif ($tile[1] == "A") { // Check if the tile is a soldier ant
                $validMove = isValidAntMove($board, $from, $to); // Check validity for soldier ant
                if (!$validMove) {
                    $_SESSION['error'] = "Invalid move for soldier ant: Invalid move from $from to $to";
                }
            }
        }
    }
    if (isset($_SESSION['error'])) {
        if (isset($board[$from])) {
            array_push($board[$from], $tile);
        } else {
            $board[$from] = [$tile];
        }
    } else {
        if (isset($board[$to])) {
            array_push($board[$to], $tile);
        } else {
            $board[$to] = [$tile];
        }
        unset($board[$from]);
        $_SESSION['player'] = 1 - $_SESSION['player'];
        include_once 'database.php';
        $db = new Database();
        $state = getState(); // Store state in a variable
        $stmt = $db->prepare(
            'INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state) VALUES (?, "move", ?, ?, ?, ?)'
        );
        $stmt->bind_param('issis', $_SESSION['game_id'], $from, $to, $_SESSION['last_move'], $state);
        $stmt->execute();
        $_SESSION['last_move'] = $db->insertId();
    }
    $_SESSION['board'] = $board;
}

header('Location: index.php');
