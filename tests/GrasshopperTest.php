<?php

use PHPUnit\Framework\TestCase;

// Zorg ervoor dat de autoloader alle klassen kan vinden
require_once __DIR__ . '../../src/board.php';
require_once __DIR__ . '../../src/insects/grasshopper.php';

class GrasshopperTest extends TestCase
{
    private $board;
    private $player;
    private $grasshopper;

    protected function setUp(): void
    {
        // Stel de testomgeving in
        $this->board = $this->createMock(Board::class);
        $this->player = 0; // Stel de speler in (0 voor White, 1 voor Black)
        $this->grasshopper = new Grasshopper($this->board, $this->player);
    }

    public function testMoveInStraightLine()
    {
        $this->board->method('getPositions')->willReturn([
            '0,0' => [['0', 'G']],
            '1,0' => [['1', 'Q']],
            '2,0' => [['1', 'A']],
            '3,0' => []
        ]);

        $from = '0,0';
        $to = '3,0';

        $this->board->expects($this->once())
            ->method('movePiece')
            ->with($this->equalTo($from), $this->equalTo($to));

        $result = $this->grasshopper->move($from, $to);
        $this->assertTrue($result);
    }

    public function testMoveNotInStraightLine()
    {
        $this->board->method('getPositions')->willReturn([
            '0,0' => [['0', 'G']],
            '1,0' => [['1', 'Q']],
            '2,0' => [['1', 'A']],
            '3,1' => []
        ]);

        $from = '0,0';
        $to = '3,1';

        $this->board->expects($this->never())
            ->method('movePiece');

        $result = $this->grasshopper->move($from, $to);
        $this->assertFalse($result);
    }

    public function testMoveJumpingOverContiguousPieces()
    {
        $this->board->method('getPositions')->willReturn([
            '0,0' => [['0', 'G']],
            '1,0' => [['1', 'Q']],
            '2,0' => [['1', 'A']],
            '3,0' => [['1', 'S']],
            '4,0' => []
        ]);

        $from = '0,0';
        $to = '4,0';

        $this->board->expects($this->once())
            ->method('movePiece')
            ->with($this->equalTo($from), $this->equalTo($to));

        $result = $this->grasshopper->move($from, $to);
        $this->assertTrue($result);
    }
}
