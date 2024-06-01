<?php
include_once 'game.php';

session_start();

$piece = $_POST['piece'] ?? null;
$to = $_POST['to'] ?? null;

if ($piece && $to) {
    $game = new Game();
    if ($game->playPiece($piece, $to)) {
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
