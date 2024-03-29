<?php

session_start();

include_once 'database.php';

$_SESSION['board'] = [];
$_SESSION['hand'] = [
    0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3], 1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]
];
$_SESSION['player'] = 0;

$db = new Database();

$db->insertGame();

$_SESSION['game_id'] = $db->getLastInsertId();

header('Location: index.php');

