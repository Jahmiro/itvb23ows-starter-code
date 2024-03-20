<?php
session_start();

// Inclusie van database.php en het verkrijgen van de databaseverbinding
$db = include_once 'database.php';
if (!$db) {
    die("Databaseverbinding mislukt");
}

// Voorbereiden en uitvoeren van de query om een nieuw spel op te slaan
$query = $db->prepare('INSERT INTO games () VALUES ()');
if (!$query->execute()) {
    die("Fout bij het uitvoeren van de query: " . $query->error);
}

// Het verkrijgen van het gegenereerde spel-ID
$_SESSION['game_id'] = $db->insert_id;

// Doorverwijzen naar index.php
header('Location: index.php');
exit(); // Zorg ervoor dat er geen code wordt uitgevoerd na de header-redirect
