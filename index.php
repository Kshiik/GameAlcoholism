<?php
session_start();

if (session_status() !== PHP_SESSION_ACTIVE) {
    die("Session error");
}

$baseHp = 10;

if (!isset($_SESSION['player_hp']) || !isset($_SESSION['server_hp']) || isset($_GET['reset'])) {
    $_SESSION['player_hp'] = $baseHp;
    $_SESSION['server_hp'] = $baseHp;
    $_SESSION['log'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['player_choice'])) {
        $playerChoice = (int)$_POST['player_choice'];
        if ($playerChoice < 1 || $playerChoice > 3) {
            $_SESSION['log'][] = "Неверное число";
        } else {
            $serverChoice = rand(1, 3);
            $damage = rand(1, 4);

            if ($playerChoice === $serverChoice) {
                $_SESSION['player_hp'] -= $damage;
                $_SESSION['log'][] = "Второй игрок угадал! Вы потеряли $damage HP.";
            } else {
                $_SESSION['server_hp'] -= $damage;
                $_SESSION['log'][] = "Второй игрок не угадал! Потерял $damage HP.";
            }

            $_SESSION['log'][] = "Ваш HP: {$_SESSION['player_hp']}, HP второго игрока: {$_SESSION['server_hp']}.";

            if ($_SESSION['player_hp'] <= 0) {
                header("Location: pageGame.php?winner=server");
                exit;
            }

            if ($_SESSION['server_hp'] <= 0) {
                header("Location: pageGame.php?winner=player");
                exit;
            }
        }
    } else {
        $_SESSION['log'][] = "Ошибка: Выберите число!"; 
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameAlcoholism</title>
</head>
<body>
    <p>Ваш HP: <?= $_SESSION['player_hp'] ?></p>
    <p>HP второго игрока: <?= $_SESSION['server_hp'] ?></p>

    <form method="post">
        <label for="player_choice">Введите число от 1 до 3:</label>
        <input type="number" name="player_choice" id="player_choice" min="1" max="3" required>
        <button type="submit">Атаковать</button>
    </form>

    <h2>История игры:</h2>
    <ul>
        <?php foreach (array_reverse($_SESSION['log']) as $logEntry): ?>
            <li><?= htmlspecialchars($logEntry) ?></li>
        <?php endforeach; ?>
    </ul>

    <form method="get">
        <button type="submit" name="reset" value="1">Заново</button>
    </form>
</body>
</html>