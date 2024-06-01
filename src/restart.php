<?php
include_once 'game.php';

session_start();

$game = new Game();
$game->restart();

header('Location: index.php');
exit();
?>
