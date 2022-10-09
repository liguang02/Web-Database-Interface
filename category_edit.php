<?php

require('common.php');
/** @var PDO $dbh Database Connection */

if (isset($_GET['id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (
            !empty($_POST['name']) &&
            is_string($_POST['name'])
        ) {
            // Transaction to allow reversion if error occurs
            $dbh->beginTransaction();

            // Update category
            $category_stmt = $dbh->prepare("UPDATE `category` SET `name` = :name, `parent_id` = :parent WHERE `id` = :id");
            if (!$category_stmt->execute([
                'name' => $_POST['name'],
                'parent' => $_POST['parent'] ? $_POST['parent'] : NULL,
                'id' => $_GET['id']
            ])) {
                $dbh->rollback();  // In case of error, rollback everything
                header("Location: category_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The category cannot be updated. Please try again!'));
                exit();
            }

            $dbh->commit();
            header("Location: categories.php");
        } else {
            header("Location: category_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Make sure the form is valid before send!'));
        }
        exit();
    }
} else {
    // Unavailable if there is no id input
    header("Location: categories.php");
}

include('home.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>Edit Category #<?= $_GET['id'] ?></ins></h1><br>
<div>
    <?php if (!empty($_GET['error'])) { ?>
        <p class="error"><?= $_GET['error'] ?></p>
    <?php }
    $stmt = $dbh->prepare("SELECT * FROM `category` WHERE `id`=?");
    if ($stmt->execute([$_GET['id']])) {
        if ($stmt->rowCount() > 0) {
            $initial = $stmt->fetchObject(); ?>
            <form method="post" enctype="multipart/form-data">
                <div class="section text-center">
                <div>
                    <label for="name">New Name: </label>
                    <input type="text" id="name" name="name" maxlength="64" required value="<?= $initial->name ?>"/><br><br>
                </div>
                <div>
                    <label for="parent">New Parent: </label>
                    <select id="parent" name="parent">
                        <option value=""></option>
                        <?php
                        $categories = $dbh->prepare("SELECT * FROM `CATEGORY` WHERE `parent_id` IS NULL");
                        $categories->execute();
                        while ($cat = $categories->fetchObject()) {
                            if ($cat->id == $initial->parent_id) {
                                var_dump("HI");
                            ?>
                                <option value="<?= $cat->id ?>" selected><?= $cat->name ?></option>
                            <?php } else {?>
                                <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                            <?php }
                        }?>
                    </select><br><br>
                </div>

                    <input class="btn btn-success" type="submit" value="Submit"/>


                    <a class="btn btn-danger" href="categories.php">Cancel and back to list</a>

                </div>
            </form>
        <?php } else {
            header("Location: categories.php");
        }
    } else {
        $dbh->rollback();  // In case of error, rollback everything
        header("Location: category_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The category cannot be updated. Please try again!'));
        exit();
    }
    ?>
</div>

