<?php
use PHPUnit\Framework\TestCase;

// Include util.php at the beginning of the test file
include_once __DIR__ . '/../src/util.php';


class MoveTest extends TestCase {
    public function testValidMove() {
        $_SESSION['player'] = 0;
        $_SESSION['board']['0,0'] = [['0', 'Q']];
        $_SESSION['hand'] = [0 => ['Q' => 1], 1 => ['Q' => 1]];

        $_POST['from'] = '0,0';
        $_POST['to'] = '1,1';

        include '../src/move.php';

        $this->assertArrayHasKey('1,1', $_SESSION['board']);
        $this->assertEquals('Q', $_SESSION['board']['1,1'][0][1]);
        $this->assertEquals(0, $_SESSION['player']);
    }
}
