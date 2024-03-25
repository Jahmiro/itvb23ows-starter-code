<?php

// Functie om de databaseverbinding op te zetten
function getDBConnection()
{
    $mysqli = new mysqli('db', 'root', '', 'hive');

    if ($mysqli->connect_error) {
        die('Databaseverbinding mislukt: ' . $mysqli->connect_error);
    }

    return $mysqli;
}
