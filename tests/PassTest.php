

<?php
include_once __DIR__ . '/../src/pass.php';

class PassTest extends \PHPUnit\Framework\TestCase
{
    public function testCheckValidMovesAvailable()
    {
        // Simuleer een bord waarbij er geen geldige zetten beschikbaar zijn voor speler 0
        $board = [
            '0,0' => [['1', 'Q']], // Voorbeeld van een bezette positie door speler 1
            // Voeg hier andere posities toe die niet beschikbaar zijn voor speler 0
        ];
    
        // Simuleer een lege hand voor speler 0
        $_SESSION['hand'][0] = [];
    
        // Test of checkValidMovesAvailable correct aangeeft dat er geen geldige zetten beschikbaar zijn voor speler 0
        $this->assertFalse(checkValidMovesAvailable($board, $_SESSION['hand'], 0));
    }
    

    public function testPass()
    {
        // Simuleer de sessievariabelen en de databaseverbinding
        $_SESSION['player'] = 0;
        $_SESSION['game_id'] = 1;
        $_SESSION['last_move'] = 0;
        $_SESSION['hand'] = [
            0 => ['A' => 1, 'B' => 1], // Hand van speler 0
            1 => ['A' => 1, 'B' => 1]  // Hand van speler 1
        ];
        $_SESSION['board'] = [];

        // Verifieer dat er in eerste instantie geen geldige zetten beschikbaar zijn
        $this->assertFalse(checkValidMovesAvailable($_SESSION['board'], 'A', $_SESSION['player']));

        // Controleer of de speler correct is gewisseld
        $this->assertEquals(1, $_SESSION['player']);

        // Controleer of de laatste zet correct is bijgewerkt in de sessievariabelen
        $this->assertEquals(1, $_SESSION['last_move']);
    }
}