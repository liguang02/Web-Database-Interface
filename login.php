<?php

require('common.php');

/** @var PDO $dbh  */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $stmt = $dbh->prepare("SELECT * FROM `USERS` WHERE `username` = ? AND `password` = ?");

        if ($stmt->execute([$_POST['username'], hash('sha256', $_POST['password'])])
            && $stmt->rowCount() == 1) {
            $row = $stmt->fetchObject();
            $_SESSION['user_id'] = $row->id;
            if (empty($_GET['referer'])) {
                header("Location: dashboard.php");
            } else {
                header("Location: " . $_GET['referer']);
            }
        } else {
            header("Location: login.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Username and/or password is wrong. Please try again!'));
        }
        exit();
    } else {
        header("Location: login.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Please enter both username and password to login!'));
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Little Dreamer Music School</title>
<!--    <link rel="stylesheet" href="styles.css">-->
</head>
<body>
<h1>USER LOGIN</h1>
<div>
    <?php if (!empty($_GET['error'])) {?>
        <p><?= $_GET['error'] ?></p>
    <?php } ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>

