<?php
session_start();
include_once 'game.php';

$action = $_POST['action'] ?? null;
$game = new Game();

switch ($action) {
    case 'play':
        $piece = $_POST['piece'] ?? null;
        $to = $_POST['to'] ?? null;
        if ($piece && $to) {
            if ($game->playPiece($piece, $to)) {
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Missing parameters.";
        }
        break;

    case 'move':
        $from = $_POST['from'] ?? null;
        $to = $_POST['to'] ?? null;
        if ($from && $to) {
            if ($game->movePiece($from, $to)) {
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Missing parameters.";
        }
        break;

    case 'pass':
        $game->passTurn();
        header('Location: index.php');
        exit();
        break;

    case 'restart':
        $game->restart();
        header('Location: index.php');
        exit();
        break;

    case 'undo':
        break;

    default:
        $_SESSION['error'] = "Invalid action.";
        break;
}

header('Location: index.php');
exit();
?>
