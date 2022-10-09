<?php

require('common.php');
/** @var PDO $dbh Database Connection */

if (isset($_GET['id'])) {
    $tclass_stmt = $dbh->prepare("SELECT * FROM `tailored_class` WHERE `id` = ?");
    if ($tclass_stmt->execute([$_GET['id']]) && $tclass_stmt->rowCount() > 0) {
        $tclass = $tclass_stmt->fetchObject();
    } else {
        // Unavailable if category not found
        header("Location: tclass.php");
    }
} else {
    // Unavailable if there is no id input
    header("Location: tclass.php");
}

require_once('index.html');
?>

<h1>Tailored Class Details</h1>
<div>
    <p>Summary: <?= $tclass->summary ?></p>
    <p>Start Date: <?= $tclass->start_date ?></p>
    <p>End Date: <?= $tclass->end_date ?></p>
    <p>Quote: <?= $tclass->quote ?></p>
    <p>Other Information: <?= $tclass->otherInfo ?></p>
    <p>Student ID: <?= $tclass->student_id ?></p>

</div>
