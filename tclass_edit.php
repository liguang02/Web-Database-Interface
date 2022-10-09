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
            !empty($_POST['summary']) &&
            is_string($_POST['summary'])
        ) {
            // Transaction to allow reversion if error occurs
            $dbh->beginTransaction();

            // Update category
            $category_stmt = $dbh->prepare("UPDATE `tailored_class` SET
    `summary`= :summary, `start_date`=:start_date,`end_date`= :end_date,`quote`= :quote,`otherInfo`= :otherInfo,`student_id`= :student_id WHERE `id` = :id");
            if (!$category_stmt->execute([
                'summary' => $_POST['summary'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'quote' => $_POST['quote'],
                'otherInfo' => $_POST['otherInfo'],
                'student_id' => $_POST['student_id'],
                'id' => $_GET['id']
            ])) {
                $dbh->rollback();  // In case of error, rollback everything
                header("Location: tclass_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The category cannot be updated. Please try again!'));
                exit();
            }

            $dbh->commit();
            header("Location: tclass.php");
        } else {
            header("Location: tclass_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Make sure the form is valid before send!'));
        }
        exit();
    }
} else {
    // Unavailable if there is no id input
    header("Location: tclass.php");
}

require_once('index.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>Edit Tailored Class</ins></h1>
<div>
    <?php if (!empty($_GET['error'])) { ?>
        <p class="error"><?= $_GET['error'] ?></p>
    <?php }
    $stmt = $dbh->prepare("SELECT * FROM `tailored_class` WHERE `id`=?");
    if ($stmt->execute([$_GET['id']])) {
        if ($stmt->rowCount() > 0) {
            $initial = $stmt->fetchObject(); ?>

            <form method="post" enctype="multipart/form-data">
                <div class="section text-center">
                <div>
                    <label for="summary">New Summary: </label>
                    <input type="text" id="summary" name="summary" maxlength="64" required value="<?= $initial->summary ?>"/><br><br>
                </div>
                <div>
                    <label for="start_date">New Start Date: </label>
                    <input type="date" id="start_date" name="start_date" maxlength="64" required value="<?= $initial->start_date ?>"/><br><br>
                </div>
                <div>
                    <label for="end_date">New End Date: </label>
                    <input type="date" id="end_date" name="end_date" maxlength="64" required value="<?= $initial->end_date ?>"/><br><br>
                </div>
                <div>
                    <label for="quote">New Quote: </label>
                    <input type="text" id="quote" name="quote" maxlength="64" required value="<?= $initial->quote ?>"/><br><br>
                </div>
                <div>
                    <label for="otherInfo">New Other Information: </label>
                    <input type="text" id="otherInfo" name="otherInfo" maxlength="64" required value="<?= $initial->otherInfo ?>"/><br><br>
                </div>
                <div>
                    <label for="student_id">New Student ID: </label>
                    <input type="text" id="student_id" name="student_id" maxlength="64" required value="<?= $initial->student_id ?>"/><br><br>
                </div>
                    <input class="btn btn-success" type="submit" value="Update"/>
                    <a class="btn btn-danger" href="tclass.php">Cancel and back to list</a>
                </div>
            </form>
        <?php } else {
            header("Location: tclass.php");
        }
    } else {
        $dbh->rollback();  // In case of error, rollback everything
        header("Location: tclass_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The Tailored Class cannot be updated. Please try again!'));
        exit();
    }
    ?>
</div>

