<?php

class Board {
    private $positions;

    public function __construct() {
        $this->positions = [];
    }

    public function addPiece($piece, $position) {
        $this->positions[$position][] = $piece;
    }

    public function movePiece($from, $to) {
        if (isset($this->positions[$from])) {
            $piece = array_pop($this->positions[$from]);
            $this->positions[$to][] = $piece;
            if (empty($this->positions[$from])) {
                unset($this->positions[$from]);
            }
        }
    }

    public function getPositions() {
        return $this->positions;
    }
}
?>
