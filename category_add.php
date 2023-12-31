<?php

require('common.php');
/** @var PDO $dbh Database Connection */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !empty($_POST['name']) &&
        is_string($_POST['name'])
    ) {
        // Transaction to allow reversion if error occurs
        $dbh->beginTransaction();

        // Add the new category and get its primary key
        $category_stmt = $dbh->prepare("INSERT INTO `category` (`name`, `parent_id`) VALUE (:name, :parent)") ;
        if (!$category_stmt->execute([
            'name' => $_POST['name'],
            'parent' => $_POST['parent']? $_POST['parent'] : NULL
        ])) {
            $dbh->rollback();  // In case of error, rollback everything
            header("Location: category_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The new category cannot be added. Please try again!'));
            exit();
        }
        $dbh->commit();
        header("Location: categories.php");
    } else {
        header("Location: category_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Make sure the form is valid before send!'));
    }
    exit();
}

require_once('home.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>Add New Category</ins></h1><br>
<div>
    <?php if (!empty($_GET['error'])) { ?>
        <p class="error"><?= $_GET['error'] ?></p>
    <?php } ?>

    <form method="post" enctype="multipart/form-data">
        <div class="section text-center">
        <div>
            <label for="name">Name: </label>
            <input type="text" id="name" name="name" maxlength="64" required/><br><br>
        </div>
        <div>
            <label for="parent">Parent: </label>
            <select id="parent" name="parent">
                <option value="" selected></option>
                <?php
                $categories = $dbh->prepare("SELECT * FROM `CATEGORY` WHERE `parent_id` IS NULL");
                $categories->execute();
                while ($cat = $categories->fetchObject()) {
                    ?>
                    <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                <?php } ?>
            </select><br><br>
        </div>
            <input class="btn btn-success" type="submit" value="Submit"/>
            <a class="btn btn-danger" href="categories.php">Cancel and back to list</a>
        </div>
    </form>
</div>

