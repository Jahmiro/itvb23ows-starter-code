<?php

class Player {
    private $name;
    private $pieces;

    public function __construct($name) {
        $this->name = $name;
        $this->pieces = [
            'Q' => 1,
            'A' => 3,
            'G' => 3,
            'S' => 2, 
        ];
    }

    public function getName() {
        return $this->name;
    }

    public function getPieces() {
        return $this->pieces;
    }

    public function removePiece($piece) {
        if (isset($this->pieces[$piece]) && $this->pieces[$piece] > 0) {
            $this->pieces[$piece]--;
            return true;
        }
        return false;
    }
}
?>
