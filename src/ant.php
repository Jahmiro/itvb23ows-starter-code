<?php

class Ant {
    private $board;
    private $player;
    private $from;
    private $to;

    public function __construct($board, $player) {
        $this->board = $board;
        $this->player = $player;
    }

    public function move($from, $to) {
        $this->from = $from;
        $this->to = $to;

        if ($this->isValidMove()) {
            $this->board->movePiece($this->from, $this->to);
            return true;
        } else {
            return false;
        }
    }

    private function isValidMove() {
        if ($this->from == $this->to) {
            return false; 
        }

        if (!$this->canSlide()) {
            return false; 
        }

        if (!$this->maintainsHive()) {
            return false;
        }

        return true;
    }

    private function canSlide() {
        $adjacentPositions = $this->getAdjacentPositions($this->to);
        foreach ($adjacentPositions as $pos) {
            if (isset($this->board->getPositions()[$pos])) {
                return true; // The destination is adjacent to another piece
            }
        }
        return false;
    }

    private function maintainsHive() {
        $boardCopy = $this->board->getPositions();
        unset($boardCopy[$this->from]);
        return $this->isHiveConnected($boardCopy);
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

    private function isHiveConnected($board) {
        $visited = [];
        $positions = array_keys($board);
        $this->dfs($positions[0], $board, $visited);
        return count($visited) == count($positions);
    }

    private function dfs($pos, &$board, &$visited) {
        $visited[$pos] = true;
        foreach ($this->getAdjacentPositions($pos) as $adjPos) {
            if (isset($board[$adjPos]) && !isset($visited[$adjPos])) {
                $this->dfs($adjPos, $board, $visited);
            }
        }
    }
}
?>
