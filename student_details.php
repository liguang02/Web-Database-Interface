<?php

require('common.php');
/** @var PDO $dbh Database Connection */

if (isset($_GET['id'])) {
    $student_stmt = $dbh->prepare("SELECT * FROM `STUDENTS` WHERE `id` = ?");
    if ($student_stmt->execute([$_GET['id']]) && $student_stmt->rowCount() > 0) {
        $student = $$student_stmt->fetchObject();
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
    <th>ID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Address</th>
    <th>Phone Number</th>
    <th>Date of Birth</th>
    <th>Email</th>
    <th>Subscribed?</th>
    <th>Action</th>

    <p>First Name: <?= $student->firstName ?></p>
    <p>Last Name: <?= $student->surname ?></p>
    <p>Address: <?= $student->address ?></p>
    <p>Phone Number: <?= $student->phone?></p>
    <p>Date of Birth: <?= $student->dob ?></p>
    <p>Email: <?= $student->email ?></p>
    <p>Subscribed: <?= $student->subscribe ?></p>
    <p>Courses Enrolled: <?= $student->student_id ?></p>



</div>
