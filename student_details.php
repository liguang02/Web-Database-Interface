<?php

require('common.php');
/** @var PDO $dbh Database Connection */

if (isset($_GET['id'])) {
    $student_stmt = $dbh->prepare("SELECT * FROM `students` WHERE `id` = ?");
    if ($student_stmt->execute([$_GET['id']]) && $student_stmt->rowCount() > 0) {
        $student = $student_stmt->fetchObject();
        $enrolments = $dbh->prepare("SELECT * FROM `enrolment` WHERE `student_id` = ?");
    } else {
        // Unavailable if category not found
        header("Location: students.php");
    }
} else {
    // Unavailable if there is no id input
    header("Location: students.php");
}

require_once('home.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<h1 class="display-3 text-center"><ins>Student Details</ins></h1>
<br><hr>
<div class="container text-end">
    <a class="btn btn-danger" href="students.php">Cancel and back to list</a>
</div>
<div class="container bg-light">
    <p><font size="6"><b>Student ID:</b> <?= $student->id ?></font></p>
    <p><font size="6"><b>First Name:</b> <?= $student->firstName ?></font></p>
    <p><font size="6"><b>Last Name:</b> <?= $student->surname ?></font></p>
    <p><font size="6"><b>Address:</b> <?= $student->address ?></font></p>
    <p><font size="6"><b>Phone Number:</b> <?= $student->phone?></font></p>
    <p><font size="6"><b>Date of Birth:</b> <?= $student->dob ?></font></p>
    <p><font size="6"><b>Email:</b> <?= $student->email ?></font></p>
    <?php
    if ($student->subscribe) {
        $subscribed = "Yes";
    }else{
        $subscribed = "No";
    }
    ?>
    <p><font size="6"><b>Subscribed: <?= $subscribed ?></font></p>
    <br>
    <p class="display-4 text-center"><ins>Courses Enrolled: </ins></p>
    <?php if ($enrolments->execute([$student->id]) && $enrolments->rowCount() > 0) { ?>
        <table class="table table-hover">
            <thead class="table-dark">
            <tr>
                <th>Enrolment ID</th>
                <th>Course Name</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($enrolment = $enrolments->fetchObject()) { ?>
                <tr>
                    <td><?= $enrolment->id ?></td>
                    <?php
                    $course = $dbh->prepare("SELECT `name` FROM `course` WHERE `id`=?");
                    $course->execute([$enrolment->course_id]);
                    ?>
                    <td><?= $course->fetchObject()->name ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else {?>
        <p>No courses enrolled!</p>
    <?php } ?>
</div>
