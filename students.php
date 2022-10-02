<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

$students = $dbh->prepare("SELECT * FROM `students`");

include('index.html');
?>

<h1>STUDENT LIST</h1>
<div class = "center row">
    <a href="student_add.php">Add new student record</a>

</div>
<div>
    <?php if ($students->execute() && $students->rowCount() > 0) { ?>
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
                <th>hasSubscribed</th>
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
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else {?>
        <p>There's no data in student</p>
    <?php } ?>
</div>
</body>
</html>
