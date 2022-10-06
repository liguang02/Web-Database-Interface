<?php

require('common.php');
/** @var PDO $dbh Database Connection */

if (isset($_GET['id'])) {
    $category_stmt = $dbh->prepare("SELECT * FROM `category` WHERE `id` = ?");
    if ($category_stmt->execute([$_GET['id']]) && $category_stmt->rowCount() > 0) {
        $category = $category_stmt->fetchObject();
    } else {
        // Unavailable if category not found
        header("Location: categories.php");
    }
} else {
    // Unavailable if there is no id input
    header("Location: categories.php");
}

include('index.html');
?>

<h1>Category Details</h1>
<div>
    <p>Name: <?= $category->name ?></p>
</div>
