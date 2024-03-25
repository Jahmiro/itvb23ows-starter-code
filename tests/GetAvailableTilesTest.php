<?php

use PHPUnit\Framework\TestCase;

include_once(__DIR__ . '/../src/util.php');


class GetAvailableTilesTest extends TestCase
{
    public function testPlayer0Hand()
    {
        $hand = [
            ['A' => 3, 'B' => 0, 'C' => 1], // Hand van speler 0
            ['A' => 0, 'B' => 2, 'C' => 0], // Hand van speler 1
        ];

        $availableTilesPlayer0 = getAvailableTiles($hand, 0);
        $expectedPlayer0 = ['A', 'A', 'A', 'C'];
        $this->assertEquals($expectedPlayer0, $availableTilesPlayer0);
    }

    public function testPlayer1Hand()
    {
        $hand = [
            ['A' => 3, 'B' => 0, 'C' => 1], // Hand van speler 0
            ['A' => 0, 'B' => 2, 'C' => 0], // Hand van speler 1
        ];

        $availableTilesPlayer1 = getAvailableTiles($hand, 1);
        $expectedPlayer1 = ['B', 'B'];
        $this->assertEquals($expectedPlayer1, $availableTilesPlayer1);
    }
}
