<?php
include_once 'game.php';

session_start();

$game = new Game();
$game->passTurn();

header('Location: index.php');
exit();
?>
