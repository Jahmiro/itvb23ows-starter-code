<?php
use PHPUnit\Framework\TestCase;

class PlayQAtMoveFourTest extends TestCase {
    public function testWhitePlayerMustPlayQueenBeeOnTurn4() {
        $_SESSION['player'] = 0;
        $_SESSION['board'] = [
            '0,0' => [['0', 'Q']],
            '1,0' => [['0', 'B']],
            '1,-1' => [['0', 'S']],
            '0,-1' => [['0', 'A']],
            '-1,0' => [['0', 'G']],
            '-1,1' => [['0', 'A']],
            '0,1' => [['0', 'S']]
        ];
        $_SESSION['hand'] = [
            0 => ['Q' => 1, 'B' => 0, 'S' => 0, 'A' => 0, 'G' => 0],
            1 => ['Q' => 0, 'B' => 0, 'S' => 0, 'A' => 0, 'G' => 0]
        ];

        $_POST['piece'] = 'B'; 
        $_POST['to'] = '1,1';

        include 'play.php';

        $this->assertArrayHasKey('error', $_SESSION);
        $this->assertEquals("Must play queen bee", $_SESSION['error']);
    }
}
