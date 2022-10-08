<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

$tailored_class = $dbh->prepare("SELECT * FROM `tailored_class`");

include('index.html');
?>
<h1>TAILORED CLASS</h1>
<div>
    <a href="tclass_add.php">Add a new tailored class</a>
    <form method="post" action="tclass_delete.php" id="tailored-delete-form">
        <input type="submit" value="Delete selected tailored classes">


        <?php if ($tailored_class->execute() && $tailored_class->rowCount() > 0) { ?>
            <table>
                <thead>
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Summary</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Student ID</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                <?php while ($tailored = $tailored_class->fetchObject()): ?>
                    <tr>
                        <td><input type="checkbox" name="tailored_ids[]" value="<?= $tailored->id ?>"/></td>
                        <td><?= $tailored->id ?></td>
                        <td><?= $tailored->summary ?></td>
                        <td><?= $tailored->start_date ?></td>
                        <td><?= $tailored->end_date ?></td>
                        <td><?= $tailored->student_id ?></td>
                        <td>
                            <a href="tclass_details.php?id=<?= $tailored->id ?>">View details</a>
                            <a href="tclass_edit.php?id=<?= $tailored->id ?>">Edit</a>
                            <button type="submit" name="tailored_ids[]" value="<?= $tailored->id ?>">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php } else {?>
            <p>There's no data in tailored</p>
        <?php } ?>
    </form>
</div>
</body>
</html>