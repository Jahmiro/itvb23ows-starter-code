<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/grasshopper.php';


class GrasshopperTest extends TestCase
{
    public function testValidMove()
    {
        $board = [
            "0,0" => [[1, "G"]],
            "0,1" => [[1, "Q"]],
        ];

        $this->assertTrue(isValidMove($board, "0,0", "0,2"));
    }
}
