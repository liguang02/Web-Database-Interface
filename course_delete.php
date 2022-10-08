<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

// It's a good idea to first check if the required fields are valid
if (isset($_POST['course_ids']) && is_array($_POST['course_ids'])) {
    // MySQL supports condition in a range in the format of a, b, c, d
    // Thus we'll need to generate the same number of ? placeholders depend on how many items in the form
    $stmt_placeholders = implode(', ', array_fill(0, count($_POST['course_ids']), '?'));

    $images_stmt = $dbh->prepare("SELECT * FROM `course_image` WHERE `course_id` IN ($stmt_placeholders)");
    if ($images_stmt->execute($_POST['course_ids']) && $images_stmt->rowCount() > 0) {
        while ($course_image = $images_stmt->fetchObject()) {
            unlink(dirname($_SERVER["SCRIPT_FILENAME"]) . "/course_images/" . $course_image->filePath);
        }
    }

    $delete_course_stmt = $dbh->prepare("DELETE FROM `course` WHERE `id` IN ($stmt_placeholders)");

    $delete_course_stmt->execute($_POST['course_ids']);
}

// Finally, send the user back to the previous location
header('Location: ' . $_SERVER['HTTP_REFERER']);
