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

include('home.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>Course Details</ins></h1>
<div class="container text-end">
    <a class="btn btn-danger" href="courses.php">Cancel and back to list</a>
</div>
<div class="container">
    <p><font size="6"><b>Name:</b> <?= $course->name ?></font></p>
    <p><font size="6"><b>Price:</b> $<?= $course->price ?></font></p>
    <?php
    $cat = $dbh->prepare("SELECT * FROM `CATEGORY` WHERE `id`=?");
    $cat->execute([$course->category_id]);
    $category = $cat->fetchObject();
    if ($category->parent_id) {
        $parent = $dbh->prepare("SELECT * FROM `CATEGORY` WHERE `id` = ?");
        $parent->execute([$category->parent_id]);
        ?>
        <p><font size="6"><b>Category:</b> <?= $parent->fetchObject()->name." - ".$category->name ?></font></p>
    <?php } else {?>
        <p><font size="6"><b>Category:</b> <?= $category->name ?></font></p>
    <?php } ?>
    <?php
    // Get images
    $image_stmt = $dbh->prepare("SELECT * FROM `course_image` WHERE `course_id` = ?");
    if ($image_stmt->execute([$course->id]) && $image_stmt->rowCount() > 0) {
        while ($course_image = $image_stmt->fetchObject()) { ?>
            <div class="row">
                <p><img src="course_images/<?= $course_image->filePath ?>" width= 30% height="auto"></p>
            </div>
        <?php }
    } ?>

</div>
