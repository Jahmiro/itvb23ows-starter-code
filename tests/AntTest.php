<?php
use PHPUnit\Framework\TestCase;

class AntTest extends TestCase
{
    public function testAntMovement()
    {
        // Mocking the board
        $board = [
            '0,0' => [['player', 'A']], // Initial position of the ant
            '0,1' => [['player', 'Q']], // Stone at (0,1)
        ];

        // Move the ant from (0,0) to (0,1)
        $board['0,1'] = $board['0,0'];
        unset($board['0,0']);

        // Asserting the initial move
        $this->assertEquals(['0,1' => [['player', 'A']]], $board);

        // Move the ant from (0,1) to (-1,2)
        $board['-1,2'] = $board['0,1'];
        unset($board['0,1']);

        // Asserting the final move
        $this->assertEquals(['-1,2' => [['player', 'A']]], $board);
    }
}
?>
