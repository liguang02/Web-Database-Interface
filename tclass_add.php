<?php

require('common.php');
/** @var PDO $dbh Database Connection */

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



    if (
        !empty($_POST['summary']) &&
        is_string($_POST['summary'])&&
        !empty($_POST['start_date']) &&
        !empty($_POST['end_date']) &&
        $_POST['start_date'] < $_POST['end_date'] &&
        !empty($_POST['student_id'])
    ) {
        // Transaction to allow reversion if error occurs
        $dbh->beginTransaction();

        // Add the new category and get its primary key
        $tailored_stmt = $dbh->prepare("INSERT INTO `TAILORED_CLASS` (`summary`,`start_date`,`end_date`,`quote`,`otherInfo`,`student_id`) VALUES
    (:summary, :start_date, :end_date, :quote, :otherInfo, :student_id)");
        if (!$tailored_stmt->execute([
            'summary' => $_POST['summary'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'quote' => $_POST['quote'],
            'otherInfo' => $_POST['otherInfo'],
            'student_id' => $_POST['student_id']
        ])) {
            $dbh->rollback();  // In case of error, rollback everything
            header("Location: tclass_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The new tailored class cannot be added. Please try again!'));
            exit();
        }
        $dbh->commit();
        header("Location: tclass.php");
    } else {
        header("Location: tclass_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Make sure the form is valid before send!'));
    }
    exit();
}

include('home.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>Add New Tailored Class</ins></h1><br>
<div class="center">
    <?php if (!empty($_GET['error'])) { ?>
        <p class="error"><?= $_GET['error'] ?></p>
    <?php } ?>

    <form method="post" enctype="multipart/form-data">
        <div class="section text-center">
        <div>
            <label for="summary">Summary: </label>
            <input type="text" id="summary" name="summary" maxlength="100" required/><br><br>
        </div>
        <div>
            <label for="start_date">Start Date: </label>
            <input type="date" id="start_date" name="start_date" maxlength="64" required/><br><br>
        </div>
        <div>
            <label for="end_date">End Date: </label>
            <input type="date" id="end_date" name="end_date" after="start_date" maxlength="64" required /><br><br>
        </div>
        <div>
            <label for="quote">Quote: </label>
            <input type="text" id="quote" name="quote" maxlength="64" required/><br><br>
        </div>
        <div>
            <label for="otherInfo">Other Info: </label>
            <input type="text" id="otherInfo" name="otherInfo" maxlength="64" required/><br><br>
        </div>
        <div>
            <label for="student_id">Student ID: </label>
            <input type="text" id="student_id" name="student_id" maxlength="64" required/><br><br>
        </div>
        <br><hr>
        <input class="btn btn-success" type="submit" value="Submit"/>
        <a class="btn btn-danger"  href="tclass.php">Cancel and back to list</a>
        </div>
    </form>
</div>

