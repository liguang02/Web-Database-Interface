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

include('index.html');
?>

<h1>Student Details</h1>
<div>
    <p>Student ID: <?= $student->id ?></p>
    <p>First Name: <?= $student->firstName ?></p>
    <p>Last Name: <?= $student->surname ?></p>
    <p>Address: <?= $student->address ?></p>
    <p>Phone Number: <?= $student->phone?></p>
    <p>Date of Birth: <?= $student->dob ?></p>
    <p>Email: <?= $student->email ?></p>
    <?php
    if ($student->subscribe) {
        $subscribed = "Yes";
    }else{
        $subscribed = "No";
    }
    ?>
    <p>Subscribed: <?= $subscribed ?></p>
    <br>
    <p>Courses Enrolled: </p>
    <?php if ($enrolments->execute([$student->id]) && $enrolments->rowCount() > 0) { ?>
        <table>
            <thead>
            <tr>
                <th>ID</th>
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
