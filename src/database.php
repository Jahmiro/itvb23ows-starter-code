<?php

function getState()
{
    return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
}

function setState($state)
{
    list($a, $b, $c) = unserialize($state);
    $_SESSION['hand'] = $a;
    $_SESSION['board'] = $b;
    $_SESSION['player'] = $c;
}

// Databaseverbinding
$mysqli = new mysqli('db', 'root', '', 'hive');

// Controleer op fouten
if ($mysqli->connect_error) {
    die('Databaseverbinding mislukt: ' . $mysqli->connect_error);
}

// Geef de verbinding terug
return $mysqli;
