<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

$tailored_class = $dbh->prepare("SELECT * FROM `tailored_class`");

require_once('index.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>TAILORED CLASS</ins></h1>
<div>
    <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item" style="padding-left:5.0in">
                <a class="btn btn-success" href="tclass_add.php">Add a new tailored class</a>

            </li>
            <li class="nav-item" style="padding-left:0.2in">
                <form method="post" action="tclass_delete.php" id="tailored-delete-form">
                    <input class="btn btn-danger" type="submit" value="Delete selected tailored classes">
            </li>
        </ul>
    </nav>



        <?php if ($tailored_class->execute() && $tailored_class->rowCount() > 0) { ?>
            <table class="table table-hover">
                <thead class="table-dark">
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
                            <a class="btn btn-info" href="tclass_details.php?id=<?= $tailored->id ?>">Details</a>
                            <a class="btn btn-secondary" href="tclass_edit.php?id=<?= $tailored->id ?>">Edit</a>
                            <a class="btn btn-danger" href="tclass_delete.php?id=<?= $tailored->id ?>">Delete</a>
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