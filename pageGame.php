<?php
session_start();
if (session_status() !== PHP_SESSION_ACTIVE) {
    die("Session error");
}


$winner = $_GET['winner'] ?? null;
if (!$winner) {
    header("Location: index.php?page=game1");
    exit;
}

$message = $winner === 'player' ? "Поздравляем, вы победили второго игрока!" : "К сожалению, победил игрок. Попробуйте снова!";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Игра окончена</title>
</head>
<body>
    <h1><?= htmlspecialchars($message) ?></h1>
    <form method="get" action="index.php">
        <button type="submit" name="reset" value="1">Начать заново</button>
    </form>
</body>
</html>