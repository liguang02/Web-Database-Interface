<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $students = $dbh->prepare("SELECT * FROM `students` WHERE `subscribe`");
} else {
    $students = $dbh->prepare("SELECT * FROM `students`");
}

require_once('index.html');
?>
<br><hr>
<h1 class="display-3 text-center"><ins>STUDENT LIST</ins></h1>
<nav class="navbar navbar-expand-sm bg-light navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item" style="padding-left:5.0in">
            <a class="btn btn-primary" href="student_pdf.php">Print PDF</a>
        </li>
        <li class="nav-item" style="padding-left:0.2in">
            <a class="btn btn-primary" href="student_add.php">Add new student record</a>
        </li>
        <li class="nav-item" style="padding-left:0.2in">
            <form method="post">
                <button class="btn btn-primary" type="submit">Get List of Subscribed Emails</button>
            </form>
        </li>
    </ul>
</nav>

<div>
    <?php if ($students->execute() && $students->rowCount() > 0) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {?>
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Date of Birth</th>
                    <th>Email</th>
                    <th>Subscribed?</th>
                    <th>Action</th>

                </tr>
                </thead>
                <tbody>
                <?php while ($student = $students->fetchObject()) { ?>
                    <tr>
                        <td><?= $student->id ?></td>
                        <td class="table-cell-left"><?= $student->firstName ?></td>
                        <td class="table-cell-left"><?= $student->surname ?></td>
                        <td class="table-cell-left"><?= $student->address ?></td>
                        <td class="table-cell-left"><?= $student->phone ?></td>
                        <td class="table-cell-left"><?= $student->dob ?></td>
                        <td class="table-cell-left"><?= $student->email ?></td>
                        <?php
                        if ($student->subscribe) {
                            $subscribed = "Yes";
                        }else{
                            $subscribed = "No";
                        }
                        ?>
                        <td class="table-cell-left"><?= $subscribed?></td>
                        <td>
                            <a href="student_edit.php? id=<?=$student->id?>">Update</a>
                            <a href="student_delete.php? id=<?=$student->id?>">Delete</a>
                            <a href="student_details.php? id=<?=$student->id?>">Details</a>

                        </td>
                    </tr>
                <?php } ?>
            <?php } else {?>
                <div>
                    <a class="btn btn-secondary"href="students.php">Back to List of Students</a>
                </div>
                <table class="table table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($student = $students->fetchObject()) { ?>
                        <tr>
                            <td><?= $student->id ?></td>
                            <td class="table-cell-left"><?= $student->email ?></td>
                        </tr>
                    <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    <?php } else {?>
        <p>There's no data in student</p>
    <?php } ?>
</div>
</body>
</html>
