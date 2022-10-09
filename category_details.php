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

include('home.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>Category Details</ins></h1>
<br>
<div class="container text-end">
    <a class="btn btn-danger" href="categories.php">Cancel and back to list</a>
</div>
<div class="container">
    <p><font size="6"><b>Name:</b> <?= $category->name ?></font></p>
    <?php
    $parent = $dbh->prepare("SELECT `name` FROM `CATEGORY` WHERE `id`=?");
    $parent->execute([$category->parent_id]);
    if ($parent->rowCount() > 0) {
    ?>
        <p><font size="6"><b>Parent Category:</b> <?=$parent->fetchObject()->name?></font></p>
    <?php } ?>

</div>
