<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

if (isset($_POST['course_name'])) {
    $courses = $dbh->prepare("SELECT * FROM `course` WHERE `name` LIKE '%{$_POST['course_name']}%'");
} else {
    $courses = $dbh->prepare("SELECT * FROM `course`");

}
require_once('index.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>COURSES</ins></h1>
<br>
<div>
    <form class="text-center" method="post">
        <input type="text" name="course_name" placeholder="Search for Course">
        <button type="submit">Search</button>
    </form>
    <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <ul class="navbar-nav">

            <li class="nav-item" style="padding-left:5.5in">
                <a class="btn btn-success" href="course_add.php">Add New Course</a>
            </li>
            <li class="nav-item" style="padding-left:0.2in">
                <form method="post">
                    <button class="btn btn-primary" type="submit">Get List of Subscribed Emails</button>
                </form>
            </li>

        </ul>
    </nav>


        <?php if ($courses->execute() && $courses->rowCount() > 0) { ?>
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($course = $courses->fetchObject()) { ?>
                    <tr>
                        <td><input type="checkbox" name="course_ids[]" value="<?= $course->id ?>"/></td>
                        <td><?= $course->id ?></td>
                        <td class="table-cell-left"><a href="course_details.php?id=<?= $course->id ?>"><?= $course->name ?></a></td>
                        <td class="table-cell-left">$<?= $course->price ?></td>
                        <?php
                            $cat = $dbh->prepare("SELECT `name` FROM `CATEGORY` WHERE `id`=?");
                            $cat->execute([$course->category_id]);
                        ?>
                        <td><?= $cat->fetchObject()->name ?></td>
                        <td>
                            <a href="course_edit.php?id=<?= $course->id ?>">Edit</a>
                            <button class="btn btn-danger"type="submit" name="course_ids[]" value="<?= $course->id ?>">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else {?>
            <p>There's no data in courses</p>
        <?php } ?>
    </form>
</div>
</body>
</html>
