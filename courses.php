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

<h1>COURSES</h1>
<div>
    <form method="post">
        <input type="text" name="course_name" placeholder="Search for Course">
        <button type="submit">Search</button>
    </form>
    <a href="course_add.php">Add new course</a>
    <form method="post" action="course_delete.php" id="courses-delete-form">
        <input type="submit" value="Delete selected courses">
        <?php if ($courses->execute() && $courses->rowCount() > 0) { ?>
            <table>
                <thead>
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
                            <button type="submit" name="course_ids[]" value="<?= $course->id ?>">Delete</button>
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
