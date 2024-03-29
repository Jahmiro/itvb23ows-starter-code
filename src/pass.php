<?php

session_start();

include_once 'util.php';
require_once 'database.php';

function isValidMove($board, $position, $tile, $player)
{
    if (!isset($board[$position]) || empty($board[$position])) {
        if (isset($_SESSION['hand'][$player][$tile]) && $_SESSION['hand'][$player][$tile] > 0) {
            return true;
        }
    }
    return false;
}

function checkValidMovesAvailable($board, $tile, $player)
{
    foreach (array_keys($board) as $position) {
        if (isValidMove($board, $position, $tile, $player)) {
            return true;
        }
    }
    return false;
}

$player = $_SESSION['player'];
$board = $_SESSION['board'];
$hand = $_SESSION['hand'][$player];

$validMovesAvailable = false;

foreach ($hand as $tile => $count) {
    if ($count > 0) {
        $validMovesAvailable = checkValidMovesAvailable($board, $tile, $player);
        if ($validMovesAvailable) {
            break;
        }
    }
}

if (!$validMovesAvailable) {

    $db = new Database();
    $stmt = $db->prepare(
        'INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state) VALUES (?, "pass", null, null, ?, ?)'
    );
    $stmt->bind_param('iis', $_SESSION['game_id'], $_SESSION['last_move'], getState());
    $stmt->execute();
    $_SESSION['last_move'] = $db->insertId();

    $_SESSION['player'] = 1 - $_SESSION['player'];
}

header('Location: index.php');
