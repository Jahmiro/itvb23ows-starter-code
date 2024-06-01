<?php
ob_start();
include_once 'game.php';

$game = new Game();
$board = $game->getBoard()->getPositions();
$players = $game->getPlayers();
$currentPlayer = $game->getCurrentPlayer();
$playerHand = $players[$currentPlayer]->getPieces();
$availablePieces = array_keys(array_filter($playerHand));
$to = [];

foreach ($GLOBALS['OFFSETS'] as $pq) {
    foreach (array_keys($board) as $pos) {
        $pq2 = explode(',', $pos);
        $to[] = ($pq[0] + $pq2[0]) . ',' . ($pq[1] + $pq2[1]);
    }
}
$to = array_unique($to);
if (!count($to)) {
    $to[] = '0,0';
}
if ($currentPlayer == 0 && count($board) == 6 && isset($playerHand['Q']) && $playerHand['Q'] > 0) {
    $availablePieces = ['Q'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hive</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <div class="board">
        <?php
        $min_p = 1000;
        $min_q = 1000;
        foreach ($board as $pos => $tile) {
            $pq = explode(',', $pos);
            if ($pq[0] < $min_p) {
                $min_p = $pq[0];
            }
            if ($pq[1] < $min_q) {
                $min_q = $pq[1];
            }
        }
        foreach (array_filter($board) as $pos => $tile) {
            $pq = explode(',', $pos);
            $h = count($tile);
            echo '<div class="tile player';
            echo $tile[$h - 1][0];
            if ($h > 1) {
                echo ' stacked';
            }
            echo '" style="left: ';
            echo ($pq[0] - $min_p) * 4 + ($pq[1] - $min_q) * 2;
            echo 'em; top: ';
            echo ($pq[1] - $min_q) * 4;
            echo 'em;">(' . $pq[0] . ',' . $pq[1] . ')<span>';
            echo $tile[$h - 1][1];
            echo '</span></div>';
        }
        ?>
    </div>
    <div class="hand">
        White:
        <?php
        foreach ($players[0]->getPieces() as $tile => $ct) {
            for ($i = 0; $i < $ct; $i++) {
                echo '<div class="tile player0"><span>' . $tile . '</span></div> ';
            }
        }
        ?>
    </div>
    <div class="hand">
        Black:
        <?php
        foreach ($players[1]->getPieces() as $tile => $ct) {
            for ($i = 0; $i < $ct; $i++) {
                echo '<div class="tile player1"><span>' . $tile . '</span></div> ';
            }
        }
        ?>
    </div>
    <div class="turn">
        Turn: <?php echo ($currentPlayer == 0) ? "White" : "Black"; ?>
    </div>
    <form method="post" action="play.php">
        <select name="piece">
            <?php
            if ($currentPlayer == 0 && count($board) == 6 && isset($playerHand['Q']) && $playerHand['Q'] > 0) {
                echo "<option value=\"Q\">Q</option>";
            } else {
                foreach ($availablePieces as $piece) {
                    echo '<option value="' . $piece . '">' . $piece . '</option>';
                }
            }
            ?>
        </select>
        <select name="to">
            <?php
            foreach ($to as $pos) {
                echo '<option value="' . $pos . '">' . $pos . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Play">
    </form>
    <form method="post" action="move.php">
        <select name="from">
            <?php
            foreach (array_keys($board) as $pos) {
                echo '<option value="' . $pos . '">' . $pos . '</option>';
            }
            ?>
        </select>
        <select name="to">
            <?php
            foreach ($to as $pos) {
                echo '<option value="' . $pos . '">' . $pos . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Move">
    </form>
    <form method="post" action="pass.php">
        <input type="submit" value="Pass">
    </form>
    <form method="post" action="restart.php">
        <input type="submit" value="Restart">
    </form>
    <strong><?php if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
            }
            unset($_SESSION['error']); ?></strong>
    <ol>
        <?php
        $stmt = $game->getDb()->getDBConnection()->prepare('SELECT * FROM moves WHERE game_id = ?');
        $stmt->bind_param('i', $_SESSION['game_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_array()) {
            echo '<li>' . $row[2] . ' ' . $row[3] . ' ' . $row[4] . '</li>';
        }
        ?>
    </ol>
    <form method="post" action="undo.php">
        <input type="submit" value="Undo">
    </form>
</body>
</html>
<?php
ob_end_flush();
?>
