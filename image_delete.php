<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

// It's a good idea to first check if the required fields are valid
if (isset($_POST['image_ids']) && is_array($_POST['image_ids'])) {
    // MySQL supports condition in a range in the format of a, b, c, d
    // Thus we'll need to generate the same number of ? placeholders depend on how many items in the form
    $stmt_placeholders = implode(', ', array_fill(0, count($_POST['image_ids']), '?'));

    $images_stmt = $dbh->prepare("SELECT * FROM `course_image` WHERE `id` IN ($stmt_placeholders)");
    if ($images_stmt->execute($_POST['image_ids']) && $images_stmt->rowCount() > 0) {
        while ($image = $images_stmt->fetchObject()) {
            unlink(dirname($_SERVER["SCRIPT_FILENAME"]) . "/course_images/" . $image->filePath);
        }
    }

    $delete_image_stmt = $dbh->prepare("DELETE FROM `course_image` WHERE `id` IN ($stmt_placeholders)");

    $delete_image_stmt->execute($_POST['image_ids']);
}

// Finally, send the user back to the previous location
header('Location: ' . $_SERVER['HTTP_REFERER']);