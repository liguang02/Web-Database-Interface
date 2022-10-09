<?php
include("common.php");
/** @var $dbh PDO */

// Redirect back to the list page, as id is not provided in the request
if (isset($_GET['id'])) {
    $query = "DELETE FROM `students` WHERE `id` = ?";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$_GET['id']]);
}
header('Location: ' . $_SERVER['HTTP_REFERER']);

