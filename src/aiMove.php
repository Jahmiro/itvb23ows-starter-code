<?php

function callAI($moveNumber, $hand, $board) {
    $url = 'http://hiveAI:5000/';
    $data = [
        'move_number' => $moveNumber,
        'hand' => $hand,
        'board' => $board
    ];
    
    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        // Handle error
        return null;
    }

    return json_decode($result, true);
}

function handleAITurn($game) {
    $moveNumber = count($game->getBoard()->getPositions());
    $hand = array_map(function($player) {
        return $player->getPieces();
    }, $game->getPlayers());
    $board = $game->getBoard()->getPositions();

    $aiMove = callAI($moveNumber, $hand, $board);

    if ($aiMove) {
        if ($aiMove[0] == 'play') {
            $game->playPiece($aiMove[1], $aiMove[2]);
        } elseif ($aiMove[0] == 'move') {
            $game->movePiece($aiMove[1], $aiMove[2]);
        } elseif ($aiMove[0] == 'pass') {
            $game->passTurn();
        }
    } else {
        $_SESSION['error'] = "Error calling AI.";
    }
}

?>
