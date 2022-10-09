<?php

/** @var $dbh PDO */

require_once("common.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Add new student</title>

<!--    <meta name="description" content="2022 S2 Lab 7 Exercise">-->
<!--    <meta name="author" content="FIT2104 Web Database Interface">-->

    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Add new client</h1>
<div class="center">
<?php
// When a POST form is present (the user submitted a new student record form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    // Check if any of the POST fields are empty (which shouldn't be!)
    foreach ($_POST as $fieldName => $fieldValue) {
        if (empty($fieldValue) && $fieldValue != 0) {
            header("Location: student_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Make sure the form is valid before send!'));
        }
    }

    // Process the update record request (if a POST form is submitted)
    // :columnname is a named placeholder (instead of ? for anonymous placeholder)
    // See https://www.php.net/manual/en/pdo.prepare.php
    $query = "INSERT INTO `STUDENTS` (`firstName`,`surname`,`address`,`phone`,`dob`,`email`,`subscribe`) VALUES (:firstName, :surname, :address, :phone, :dob, :email, :subscribe)";

    $stmt = $dbh->prepare($query);

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
    ];

    if ($stmt->execute($parameters)) {
        // Once an INSERT query is successfully executed, lastInsertId() function will be available
        // to get the primary key of previously inserted record. See https://php.net/manual/en/pdo.lastinsertid.php
        $newRecordId = $dbh->lastInsertId();
        $dbh->commit();
        header("Location: students.php");
    } else {
        $dbh->rollback();
        header("Location: student_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The new student cannot be added. Please try again!'));
    }
    exit();
}
?>
        <form method="post">
            <div class="aligned-form">
                <div class="row">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName"/>
                </div>
                <div class="row">
                    <label for="surname">Surname</label>
                    <input type="text" id="surname" name="surname"/>
                </div>
                <div class="row">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address"/>
                </div>
                <div class="row">
                    <label for="phone">Contact</label>
                    <input type="tel" id="phone" pattern="\(0[0-9]\) [0-9]{4} [0-9]{4}" name="phone"/>
                </div>
                <div class="row">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob"/>
                </div>
                <div class="row">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"/>
                </div>
                <div class="row">
                    <label for="subscribe">Subscribed?</label>
                    <select name="subscribe" id="subscribe">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>
            <div class="row center">
                <input type="submit" value="Add"/>
            </div>
            <div class="row center">
                <a href="students.php">Cancel and back to homepage</a>
            </div>
        </form>
</div>
</body>
</html>