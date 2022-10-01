<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

$courses = $dbh->prepare("SELECT * FROM `course`");

include('index.html');
?>

<h1>COURSES</h1>
<div>
    <?php if ($courses->execute() && $courses->rowCount() > 0) { ?>
        <table>
            <thead>
            <tr>
                <th>Select</th>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($course = $courses->fetchObject()) { ?>
                <tr>
                    <td><input type="checkbox" name="course_ids[]" class="course-delete" value="<?php echo $course->id; ?>"/></td>
                    <td><?= $course->id ?></td>
                    <td class="table-cell-left"><?= $course->name ?></td>
                    <td class="table-cell-left">$<?= $course->price ?></td>
                    <?php
                        $cat = $dbh->prepare("SELECT `name` FROM `CATEGORY` WHERE `id`=?");
                        $cat->execute([$course->category_id]);
                    ?>
                    <td><?= $cat->fetchObject()->name ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else {?>
        <p>There's no data in courses</p>
    <?php } ?>
</div>
</body>
</html>
