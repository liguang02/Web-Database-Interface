<?php
include("common.php");
/** @var $dbh PDO */

// Redirect back to the list page, as id is not provided in the request
if (!isset($_GET['id'])) {
    header("Location: students.php");
    die();
}

// Process to delete record request (if a POST form is submitted)
$query = "DELETE FROM `students` WHERE `id` = ?";
$stmt = $dbh->prepare($query);
if ($stmt->execute([$_GET['id']])):
    // If deletion is successful, go back to the listing page
    header("Location: students.php");
    die();
else: ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">

        <title>Delete client #<?= $_GET['id'] ?></title>

<!--        <meta name="description" content="2022 S2 Lab 7 Exercise">-->
<!--        <meta name="author" content="FIT2104 Web Database Interface">-->

        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <h1>Delete Student #<?= $_GET['id'] ?></h1>
    <div class="center">
        <?= friendlyError($stmt->errorInfo()[2]); ?>
        <div class="center row">
            <button onclick="window.history.back()">Back to previous page</button>
        </div>
    </div>

    </body>
    </html>
<?php endif; ?>
