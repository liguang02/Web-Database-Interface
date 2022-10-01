<?php
session_start();

// DB Connection
$db_name = "fit2104_a2";
$db_host = "localhost";
$db_username = "fit2104";
$db_password = "fit2104";
$dsn = "mysql:host=$db_host;dbname=$db_name";
$dbh = new PDO($dsn, $db_username, $db_password);

if (strpos($_SERVER['PHP_SELF'], '/login.php') === false) {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php?referer=' . urlencode($_SERVER['REQUEST_URI']));
    } else {
        $user_stmt = $dbh->prepare('SELECT * FROM `USERS` WHERE `id` = ?');

        if (!$user_stmt->execute([$_SESSION['user_id']]) || $user_stmt->rowCount() == 0) {
            session_destroy();
            header('Location: login.php?error=' . urlencode("Your user has been deleted"));
        }
    }
}

