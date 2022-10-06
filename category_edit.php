<?php

require('common.php');
/** @var PDO $dbh Database Connection */

if (isset($_GET['id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // PHP $_FILES error readable references
        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );

        // Allowed MIME types
        $allowedMIME = array(
            'image/jpeg',
            'image/png',
            'image/gif'
        );

        if (
            !empty($_POST['name']) &&
            is_string($_POST['name'])
        ) {
            // Transaction to allow reversion if error occurs
            $dbh->beginTransaction();

            // Update category
            $category_stmt = $dbh->prepare("UPDATE `category` SET `name` = :name WHERE `id` = :id");
            if (!$category_stmt->execute([
                'name' => $_POST['name'],
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

include('index.html');
?>

<h1>Edit Category</h1>
<div>
    <?php if (!empty($_GET['error'])) { ?>
        <p class="error"><?= $_GET['error'] ?></p>
    <?php }
    $stmt = $dbh->prepare("SELECT * FROM `category` WHERE `id`=?");
    if ($stmt->execute([$_GET['id']])) {
        if ($stmt->rowCount() > 0) {
            $initial = $stmt->fetchObject(); ?>
            <form method="post" enctype="multipart/form-data">
                <div>
                    <label for="name">New Name: </label>
                    <input type="text" id="name" name="name" maxlength="64" required value="<?= $initial->name ?>"/>
                </div>
                <div>
                    <input type="submit" value="Update"/>
                </div>
                <div>
                    <a href="categories.php">Cancel and back to list</a>
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

