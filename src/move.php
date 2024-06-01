<?php
include_once 'game.php';

session_start();

$from = $_POST['from'] ?? null;
$to = $_POST['to'] ?? null;

if ($from && $to) {
    $game = new Game();
    if ($game->movePiece($from, $to)) {
        header('Location: index.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Missing parameters.";
    header('Location: index.php');
    exit();
}
?>
