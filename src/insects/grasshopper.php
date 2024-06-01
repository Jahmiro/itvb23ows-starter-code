<?php

class Grasshopper {
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

        if (!$this->isStraightLine()) {
            return false; 
        }

        if (!$this->canJump()) {
            return false;
        }

        return true;
    }

    private function isStraightLine() {
        $fromCoords = explode(',', $this->from);
        $toCoords = explode(',', $this->to);

        return $fromCoords[0] == $toCoords[0] || $fromCoords[1] == $toCoords[1];
    }

    private function canJump() {
        $fromCoords = explode(',', $this->from);
        $toCoords = explode(',', $this->to);
        $direction = $this->getDirection($fromCoords, $toCoords);

        $current = $fromCoords;
        $jumpedOverPiece = false;
        while ($current != $toCoords) {
            $current[0] += $direction[0];
            $current[1] += $direction[1];
            $currentPos = implode(',', $current);
            if (isset($this->board->getPositions()[$currentPos])) {
                $jumpedOverPiece = true;
            } elseif ($current != $toCoords) {
                return false;
            }
        }
        return $jumpedOverPiece;
    }

    private function getDirection($fromCoords, $toCoords) {
        $dx = $toCoords[0] - $fromCoords[0];
        $dy = $toCoords[1] - $fromCoords[1];
        return [$dx == 0 ? 0 : $dx / abs($dx), $dy == 0 ? 0 : $dy / abs($dy)];
    }
}
?>
