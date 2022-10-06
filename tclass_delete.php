<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

// It's a good idea to first check if the required fields are valid
if (isset($_POST['tailored_ids']) && is_array($_POST['tailored_ids'])) {
    // MySQL supports condition in a range in the format of a, b, c, d
    // Thus we'll need to generate the same number of ? placeholders depend on how many items in the form
    $stmt_placeholders = implode(', ', array_fill(0, count($_POST['tailored_ids']), '?'));
    $delete_category_stmt = $dbh->prepare("DELETE FROM `tailored_class` WHERE `id` IN ($stmt_placeholders)");

    $delete_category_stmt->execute($_POST['tailored_ids']);
}

// Finally, send the user back to the previous location
header('Location: ' . $_SERVER['HTTP_REFERER']);
