<?php

use PHPUnit\Framework\TestCase;

// Zorg ervoor dat de autoloader alle klassen kan vinden
require_once __DIR__ . '../../src/board.php';
require_once __DIR__ . '../../src/insects/spider.php';

class SpiderTest extends TestCase
{
    private $board;
    private $player;
    private $spider;

    protected function setUp(): void
    {
        // Stel de testomgeving in
        $this->board = $this->createMock(Board::class);
        $this->player = 0; // Stel de speler in (0 voor White, 1 voor Black)
        $this->spider = new Spider($this->board, $this->player);
    }

    public function testMoveLessThanThreeSteps()
    {
        $this->board->method('getPositions')->willReturn([
            '0,0' => [['0', 'S']],
            '1,0' => [['1', 'Q']],
            '1,1' => []
        ]);

        $from = '0,0';
        $to = '1,1';

        $this->board->expects($this->never())
            ->method('movePiece');

        $result = $this->spider->move($from, $to);
        $this->assertFalse($result);
    }

    public function testMoveMoreThanThreeSteps()
    {
        $this->board->method('getPositions')->willReturn([
            '0,0' => [['0', 'S']],
            '1,0' => [['1', 'Q']],
            '1,1' => [],
            '2,1' => [],
            '3,1' => []
        ]);

        $from = '0,0';
        $to = '3,1';

        $this->board->expects($this->never())
            ->method('movePiece');

        $result = $this->spider->move($from, $to);
        $this->assertFalse($result);
    }

    public function testMoveThatCannotSlide()
    {
        $this->board->method('getPositions')->willReturn([
            '0,0' => [['0', 'S']],
            '1,0' => [['1', 'Q']],
            '1,1' => [['0', 'A']],
            '0,1' => [['0', 'G']],
            '0,2' => []
        ]);

        $from = '0,0';
        $to = '0,2';

        $this->board->expects($this->never())
            ->method('movePiece');

        $result = $this->spider->move($from, $to);
        $this->assertFalse($result);
    }
}
