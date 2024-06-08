<?php
include_once 'database.php';
include_once 'board.php';
include_once 'player.php';
include_once 'util.php';
include_once './insects/ant.php';
include_once './insects/grasshopper.php';
include_once './insects/spider.php';

class Game {
    private $board;
    private $players;
    private $currentPlayer;
    private $db;

    public function __construct($db = null) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->db = $db ?: new Database();

        if (!isset($_SESSION['board']) || !is_string($_SESSION['board']) || !isset($_SESSION['players']) || !is_string($_SESSION['players'])) {
            $this->restart();
        } else {
            $this->board = unserialize($_SESSION['board']);
            $this->players = unserialize($_SESSION['players']);
            $this->currentPlayer = $_SESSION['player'];
        }

        if (!isset($_SESSION['game_id'])) {
            $_SESSION['game_id'] = $this->createNewGame();
        }
    }

    public function setBoard($board) {
        $this->board = $board;
    }

    public function setPlayers($players) {
        $this->players = $players;
    }

    public function setCurrentPlayer($player) {
        $this->currentPlayer = $player;
    }

    private function createNewGame() {
        $db = $this->db->getDBConnection();
        $stmt = $db->prepare('INSERT INTO games () VALUES ()');
        $stmt->execute();
        return $db->insert_id;
    }

    public function restart() {
        $this->clearMoves();
        $this->board = new Board();
        $this->players = [
            new Player('White'),
            new Player('Black')
        ];
        $this->currentPlayer = 0;
        $this->saveState();

        if (!isset($_SESSION['game_id'])) {
            $_SESSION['game_id'] = $this->createNewGame();
        }
    }

    public function clearMoves() {
        $db = $this->db->getDBConnection();
        $stmt = $db->prepare('DELETE FROM moves WHERE game_id = ?');
        $stmt->bind_param('i', $_SESSION['game_id']);
        $stmt->execute();
    }

    public function saveState() {
        $_SESSION['board'] = serialize($this->board);
        $_SESSION['players'] = serialize($this->players);
        $_SESSION['player'] = $this->currentPlayer;
    }

    public function getBoard() {
        return $this->board;
    }

    public function getPlayers() {
        return $this->players;
    }

    public function getCurrentPlayer() {
        return $this->currentPlayer;
    }

    public function nextTurn() {
        $this->currentPlayer = ($this->currentPlayer + 1) % 2;
        $this->saveState();
    }

    public function getDb() {
        return $this->db;
    }

    public function playPiece($piece, $to) {
        $player = $this->currentPlayer;
        $board = $this->board->getPositions();
        $hand = $this->players[$player]->getPieces();
        $turnsPlayed = count($board);

        if (!isset($hand[$piece]) || $hand[$piece] <= 0) {
            $_SESSION['error'] = "Player does not have tile";
            return false;
        } elseif (isset($board[$to])) {
            $_SESSION['error'] = 'Board position is not empty';
            return false;
        } elseif ($turnsPlayed > 0 && !hasNeighbour($to, $board)) {
            $_SESSION['error'] = "Board position has no neighbour";
            return false;
        } elseif ($turnsPlayed > 1 && !$this->isValidPlacement($player, $to, $board)) {
            $_SESSION['error'] = "Board position has opposing neighbour or does not touch a friendly piece";
            return false;
        } elseif ($piece == 'Q' && $turnsPlayed >= 4) {
            $_SESSION['error'] = "You must place the Queen by the fourth turn";
            return false;
        } else {
            $this->board->addPiece([$player, $piece], $to);
            $this->players[$player]->removePiece($piece);
            $this->nextTurn();
            $this->saveState();

            $db = $this->db->getDBConnection();
            $stmt = $db->prepare(
                'INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state) VALUES (?, "play", ?, ?, ?, ?)'
            );
            $state = getState();
            $stmt->bind_param('issis', $_SESSION['game_id'], $piece, $to, $_SESSION['last_move'], $state);
            $stmt->execute();
            $_SESSION['last_move'] = $db->insert_id;
            return true;
        }
    }

    public function movePiece($from, $to) {
        $player = $this->currentPlayer;
        $board = $this->board->getPositions();

        if (!isset($board[$from])) {
            $_SESSION['error'] = "No piece at the 'from' position";
            return false;
        } elseif (isset($board[$to])) {
            $_SESSION['error'] = "The 'to' position is not empty";
            return false;
        } else {
            $piece = end($board[$from]);
            if ($piece[1] == 'A') { 
                $ant = new Ant($this->board, $player);
                if (!$ant->move($from, $to)) {
                    $_SESSION['error'] = "Invalid move for Ant";
                    return false;
                }
            } elseif ($piece[1] == 'G') { 
                $grasshopper = new Grasshopper($this->board, $player);
                if (!$grasshopper->move($from, $to)) {
                    $_SESSION['error'] = "Invalid move for Grasshopper";
                    return false;
                }
            } elseif ($piece[1] == 'S') {
                $spider = new Spider($this->board, $player);
                if (!$spider->move($from, $to)) {
                    $_SESSION['error'] = "Invalid move for Spider";
                    return false;
                }
            } else {
                if (!$this->canMovePiece($player, $from, $to, $board)) {
                    $_SESSION['error'] = "Invalid move";
                    return false;
                } else {
                    $this->board->movePiece($from, $to);
                }
            }
            $this->nextTurn();
            $this->saveState();

            $db = $this->db->getDBConnection();
            $stmt = $db->prepare(
                'INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state) VALUES (?, "move", ?, ?, ?, ?)'
            );
            $state = getState();
            $stmt->bind_param('issis', $_SESSION['game_id'], $from, $to, $_SESSION['last_move'], $state);
            $stmt->execute();
            $_SESSION['last_move'] = $db->insert_id;
            return true;
        }
    }

    private function canMovePiece($player, $from, $to, $board) {
        return true;
    }

    private function isValidPlacement($player, $position, $board) {
        $adjacentPositions = $this->getAdjacentPositions($position);
        $hasFriendlyNeighbour = false;

        foreach ($adjacentPositions as $adjPos) {
            if (isset($board[$adjPos])) {
                $topPiece = end($board[$adjPos]);
                if ($topPiece[0] == $player) {
                    $hasFriendlyNeighbour = true;
                } else {
                    return false;
                }
            }
        }
        return $hasFriendlyNeighbour;
    }

    private function getAdjacentPositions($position) {
        $adjacentPositions = [];
        $coords = explode(',', $position);
        $p = intval($coords[0]);
        $q = intval($coords[1]);

        foreach ($GLOBALS['OFFSETS'] as $offset) {
            $adjacentPositions[] = ($p + $offset[0]) . ',' . ($q + $offset[1]);
        }

        return $adjacentPositions;
    }

    private function isValidMove($board, $position, $tile, $player) {
        if (!isset($board[$position]) || empty($board[$position])) {
            $hand = $this->players[$player]->getPieces();
            if (isset($hand[$tile]) && $hand[$tile] > 0) {
                return true;
            }
        }
        return false;
    }

    private function checkValidMovesAvailable($board, $tile, $player) {
        foreach (array_keys($board) as $position) {
            if ($this->isValidMove($board, $position, $tile, $player)) {
                return true;
            }
        }
        return false;
    }

    public function passTurn() {
        $player = $this->currentPlayer;
        $board = $this->board->getPositions();
        $hand = $this->players[$player]->getPieces();

        $validMovesAvailable = false;

        foreach ($hand as $tile => $count) {
            if ($count > 0) {
                $validMovesAvailable = $this->checkValidMovesAvailable($board, $tile, $player);
                if ($validMovesAvailable) {
                    break;
                }
            }
        }

        if (!$validMovesAvailable) {
            $db = $this->db->getDBConnection();
            $stmt = $db->prepare(
                'INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state) VALUES (?, "pass", NULL, NULL, ?, ?)'
            );
            $state = getState();
            $stmt->bind_param('iis', $_SESSION['game_id'], $_SESSION['last_move'], $state);
            $stmt->execute();
            $_SESSION['last_move'] = $db->insert_id;

            $this->nextTurn();
        }
    }
}
?>
