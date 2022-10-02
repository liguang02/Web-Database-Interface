<?php

require('common.php');
/** @var PDO $dbh Database Connection */

if (isset($_GET['id'])) {
    $course_stmt = $dbh->prepare("SELECT * FROM `course` WHERE `id` = ?");
    if ($course_stmt->execute([$_GET['id']]) && $course_stmt->rowCount() > 0) {
        $course = $course_stmt->fetchObject();
    } else {
        // Unavailable if course not found
        header("Location: courses.php");
    }
} else {
    // Unavailable if there is no id input
    header("Location: courses.php");
}

include('index.html');
?>

<h1>Course Details</h1>
<div>
    <p>Name: <?= $course->name ?></p>
    <p>Price: $<?= $course->price ?></p>
    <?php
    $cat = $dbh->prepare("SELECT `name` FROM `CATEGORY` WHERE `id`=?");
    $cat->execute([$course->category_id]);
    ?>
    <p>Category: <?= $cat->fetchObject()->name ?></p>
    <?php
    // Get images
    $image_stmt = $dbh->prepare("SELECT * FROM `course_image` WHERE `course_id` = ?");
    if ($image_stmt->execute([$course->id]) && $image_stmt->rowCount() > 0) {
        while ($course_image = $image_stmt->fetchObject()) { ?>
            <div class="row">
                <p><img src="course_images/<?= $course_image->filePath ?>"/></p>
            </div>
        <?php }
    } ?>
</div>
