<?php
require_once('common.php');
/** @var $dbh PDO */

// Redirect back to the list page, as id is not provided in the request
if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Update student #<?= $_GET['id'] ?></title>

<!--    <meta name="description" content="2022 S2 Lab 7 Exercise">-->
<!--    <meta name="author" content="FIT2104 Web Database Interface">-->

    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Update student #<?= $_GET['id'] ?></h1>
<div class="center">
    <?php
    // When a POST form is present (the user submitted a new client record form)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
        // Check if any of the POST fields are empty (which shouldn't be!)
        foreach ($_POST as $fieldName => $fieldValue) {
            if (empty($fieldValue) && $fieldValue != 0) {
                header("Location: student_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Make sure the form is valid before send!'));
                exit();
            }
        }
        // Process the update record request (if a POST form is submitted)
        $query = "UPDATE `students` SET `firstName` = :firstName,`surname` = :surname,`address` = :address,`phone` = :phone,`dob` = :dob, `email` = :email, `subscribe`= :subscribe WHERE `id` = :id";
        $stmt = $dbh->prepare($query);

//        $query2 = "UPDATE `enrolment` SET `student_id` = :student_id,`course_id` = :course_id WHERE `id` = :id";
//        $stmt2 = $dbh->prepare($query2);

        // Here we create an array with key-value pairs - if you have noted, this essentially
        // generates the same array as $_POST. Yes, you can directly send $_POST into PDOStatement::execute()
        // and that's exactly what those named placeholders are designed for in the first place
        $parameters = [
            'firstName' => $_POST['firstName'],
            'surname' => $_POST['surname'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'dob' => $_POST['dob'],
            'email' => $_POST['email'],
            'subscribe' => $_POST['subscribe'],
            'id' => $_GET['id']  // ID must be provided when updating a record (different from inserting a record)
        ];
        $enrolparam = [
            'course_id' => $_POST['course'],
            'student_id' => $_GET['id']
        ];

        $enrol_stmt = $dbh->prepare("INSERT INTO `enrolment`(`course_id`, `student_id`) VALUES (:course_id, :student_id)");



        if ($stmt->execute($parameters) && $enrol_stmt->execute($enrolparam)) {
            // If the record is inserted into database successfully, back to client list page
            header("Location: student_edit.php?id=".$_GET['id']);
        } else {
            $dbh->rollback();  // In case of error, rollback everything
            header("Location: student_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The student cannot be updated. Please try again!'));
            exit();
        }

    } else {


    // When no POST form is submitted, get the record from database
        // Also pre-fill the data in fields, so it's easier for the user to modify the record
        $query = "SELECT * FROM `students` WHERE `id` = ?";
        $stmt = $dbh->prepare($query);
        if ($stmt->execute([$_GET['id']])) {
            $enrolments = $dbh->prepare("SELECT * FROM `enrolment` WHERE `student_id` = ?");
            if ($stmt->rowCount() > 0) {
                $record = $stmt->fetchObject(); ?>
                <form method="post">
                    <div class="aligned-form">
                        <div class="row">
                            <label for="client_id">ID</label>
                            <input type="number" id="client_id" value="<?= $record->client_id ?>" disabled/>
                        </div>
                        <div class="row">
                            <label for="Name">First Name</label>
                            <input type="text" id="firstName" name="firstName" value="<?= $record->firstName ?>"/>
                        </div>
                        <div class="row">
                            <label for="surname">Surname</label>
                            <input type="text" id="surname" name="surname" value="<?= $record->surname ?>"/>
                        </div>
                        <div class="row">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" value="<?= $record->address ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <label for="phone">Contact</label>
                        <input type="tel" id="phone" pattern="\(0[0-9]\) [0-9]{4} [0-9]{4}" name="phone" value="<?= $record->phone ?>"/>
                    </div>
                    <div class="row">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" value="<?= $record->dob?>"/>
                    </div>
                    <div class="row">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= $record->email ?>"/>
                    </div>
                    <div class="row">
                        <label for="subscribe">Subscribed?</label>
                            <select name="subscribe" id="subscribe">
                                <?php if ($record->subscribe) {?>
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                <?php } else { ?>
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                <?php } ?>
                            </select>
                    </div>
                    <div>
                        <label for="courses">New Course: </label>
                        <select id="course" name="course">
                            <?php
                            $courses = $dbh->prepare("SELECT * FROM `COURSE`");
                            $courses->execute();
                            while ($course = $courses->fetchObject()) {
                                if ($course->id == $record->course_id ) {
                                    ?>
                                    <option value="<?= $course->id ?>" selected ><?= $course->name ?></option>
                                <?php } else { ?>
                                    <option value="<?= $course->id ?>"><?= $course->name ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                    </div>
                    <div class="row center">
                        <input type="submit" value="Add Course / Update"/>
                    </div>
                    <div>
                        <p>Courses Enrolled: </p>
                        <?php if ($enrolments->execute([$record->id]) && $enrolments->rowCount() > 0) { ?>
                            <table>
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Course Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while ($enrolment = $enrolments->fetchObject()) { ?>
                                    <tr>
                                        <td><?= $enrolment->id ?></td>
                                        <?php
                                        $course = $dbh->prepare("SELECT `name` FROM `course` WHERE `id`=?");
                                        $course->execute([$enrolment->course_id]);
                                        ?>
                                        <td><?= $course->fetchObject()->name ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        <?php } else {?>
                            <p>No courses enrolled!</p>
                        <?php } ?>
                    </div>
                    <div class="row center">
                        <a href="students.php">Cancel and back to homepage</a>
                    </div>
                </form>
            <?php } else {
                header("Location: students.php");
            }
        } else {
            $dbh->rollback();  // In case of error, rollback everything
            header("Location: student_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The student cannot be updated. Please try again!'));
            exit();
        }
    } ?>
</div>
</body>
</html>
