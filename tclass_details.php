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

require_once('home.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>Tailored Class Details</ins></h1>
<div class="container text-end">
    <a class="btn btn-danger" href="tclass.php">Cancel and back to list</a>
</div>
<div class="container bg-light">
    <p><font size="6"><b>Summary:</b> <?= $tclass->summary ?></font></p>
    <p><font size="6"><b>Start Date:</b> <?= $tclass->start_date ?></font></p>
    <p><font size="6"><b>End Date:</b> <?= $tclass->end_date ?></font></p>
    <p><font size="6"><b>Quote:</b> <?= $tclass->quote ?></font></p>
    <p><font size="6"><b>Other Info:</b> <?= $tclass->otherInfo ?></font></p>
    <p><font size="6"><b>Student ID:</b> <?= $tclass->student_id ?></font></p>

</div>

