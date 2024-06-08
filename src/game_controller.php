<?php

require_once 'game.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$game = new Game();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'play') {
            $piece = $_POST['piece'];
            $to = $_POST['to'];
            $game->playPiece($piece, $to);
        } elseif ($action == 'move') {
            $from = $_POST['from'];
            $to = $_POST['to'];
            $game->movePiece($from, $to);
        } elseif ($action == 'pass') {
            $game->passTurn();
        } elseif ($action == 'restart') {
            $game->restart();
        }
    }
    header('Location: index.php');
    exit();
}
?>
