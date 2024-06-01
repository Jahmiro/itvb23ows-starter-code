<?php

class Spider {
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

        if (!$this->isThreeStepMove()) {
            return false;
        }

        if (!$this->canSlideTo($this->from, $this->to)) {
            return false; 
        }

        return true;
    }

    private function isThreeStepMove() {
        $visited = [];
        return $this->dfs($this->from, $visited, 0);
    }

    private function dfs($current, &$visited, $steps) {
        if ($steps > 3) {
            return false;
        }

        if ($steps == 3 && $current == $this->to) {
            return true;
        }

        $visited[$current] = true;
        foreach ($this->getAdjacentPositions($current) as $adjPos) {
            if (!isset($visited[$adjPos]) && $this->canSlideTo($current, $adjPos)) {
                if ($this->dfs($adjPos, $visited, $steps + 1)) {
                    return true;
                }
            }
        }
        unset($visited[$current]);
        return false;
    }

    private function canSlideTo($from, $to) {
        $adjacentPositions = $this->getAdjacentPositions($to);
        foreach ($adjacentPositions as $pos) {
            if (isset($this->board->getPositions()[$pos]) && $pos != $from) {
                return true;
            }
        }
        return false;
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
}
?>
