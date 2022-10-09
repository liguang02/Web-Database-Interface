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

<h1>STUDENT LIST</h1>
<div style = "text-align: left:100px">
    <a href="student_pdf.php">Print PDF</a>
</div>
<div class = "center row">
    <a href="student_add.php">Add new student record</a>
    <form method="post">
        <button type="submit">Get List of Subscribed Emails</button>
    </form>
</div>

<div>
    <?php if ($students->execute() && $students->rowCount() > 0) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {?>
            <table>
                <thead>
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
                    <a href="students.php">Back to List of Students</a>
                <table>
                    <thead>
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
