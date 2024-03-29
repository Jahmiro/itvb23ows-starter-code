<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/spider.php';

class SpiderTest extends TestCase
{
    public function testValidSpiderMove()
    {
        // Testcase 1: Geldige beweging van (0,0) naar (0,3)
        $board1 = [];
        $from1 = '0,0';
        $to1 = '0,3';
        $this->assertTrue(isValidSpiderMove($board1, $from1, $to1));
    }
}
