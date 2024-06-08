<?php

use PHPUnit\Framework\TestCase;

// Zorg ervoor dat de autoloader alle klassen kan vinden
require_once __DIR__ . '../../src/board.php';
require_once __DIR__ . '../../src/insects/ant.php';

class AntTest extends TestCase
{
    private $board;
    private $player;
    private $ant;

    protected function setUp(): void
    {
        // Stel de testomgeving in
        $this->board = $this->createMock(Board::class);
        $this->player = 0; // Stel de speler in (0 voor White, 1 voor Black)
        $this->ant = new Ant($this->board, $this->player);
    }

    public function testMoveToOccupiedPosition()
    {
        $this->board->method('getPositions')->willReturn([
            '0,0' => [['0', 'Q']],
            '1,0' => [['1', 'Q']],
            '1,1' => [['0', 'A']]
        ]);

        $from = '0,0';
        $to = '1,1';

        $this->board->expects($this->never())
            ->method('movePiece');

        $result = $this->ant->move($from, $to);
        $this->assertFalse($result);
    }

    public function testMoveToNonAdjacentPosition()
    {
        $this->board->method('getPositions')->willReturn([
            '0,0' => [['0', 'Q']],
            '1,0' => [['1', 'Q']]
        ]);

        $from = '0,0';
        $to = '2,2';

        $this->board->expects($this->never())
            ->method('movePiece');

        $result = $this->ant->move($from, $to);
        $this->assertFalse($result);
    }

    public function testMoveThatSplitsHive()
    {
        $this->board->method('getPositions')->willReturn([
            '0,0' => [['0', 'Q']],
            '1,0' => [['1', 'Q']],
            '1,1' => [['0', 'A']],
            '0,1' => [['0', 'S']]
        ]);

        $from = '0,0';
        $to = '2,2';

        // Simuleer dat het bord na het verplaatsen de verbinding verbreekt
        $this->board->method('getPositions')->willReturn([
            '1,0' => [['1', 'Q']],
            '1,1' => [['0', 'A']],
            '0,1' => [['0', 'S']]
        ]);

        $this->board->expects($this->never())
            ->method('movePiece');

        $result = $this->ant->move($from, $to);
        $this->assertFalse($result);
    }
}
