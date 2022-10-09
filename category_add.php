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
        $category_stmt = $dbh->prepare("INSERT INTO `category` (`name`) VALUE (:name)");
        if (!$category_stmt->execute([
            'name' => $_POST['name']
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

include('index.html');
?>

<h1>Add New Category</h1>
<div>
    <?php if (!empty($_GET['error'])) { ?>
        <p class="error"><?= $_GET['error'] ?></p>
    <?php } ?>

    <form method="post" enctype="multipart/form-data">
        <div>
            <label for="name">Name: </label>
            <input type="text" id="name" name="name" maxlength="64" required/>
        </div>
        <div>
            <input type="submit" value="Add"/>
        </div>
        <div>
            <a href="categories.php">Cancel and back to list</a>
        </div>
    </form>
</div>

